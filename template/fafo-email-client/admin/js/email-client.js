/**
 * FAFO Email Client - Gmail-like JavaScript
 */
/* global fafoEmailData, jQuery */
(function ($) {
    'use strict';

    // ============================================================
    // STATE
    // ============================================================
    const state = {
        folder:       (window.fafoInitialFolder || 'inbox'),
        page:         1,
        perPage:      50,
        search:       '',
        selectedIds:  new Set(),
        currentEmail: null,
        counts:       {},
        composePendingAttachments: [],
    };

    // ============================================================
    // API HELPERS
    // ============================================================
    async function apiGet(path, params = {}) {
        const url = new URL(fafoEmailData.restUrl + path, window.location.href);
        Object.entries(params).forEach(([k, v]) => url.searchParams.set(k, v));
        const res = await fetch(url.toString(), {
            headers: { 'X-WP-Nonce': fafoEmailData.nonce },
        });
        if (!res.ok) throw new Error(`API error ${res.status}`);
        return res.json();
    }

    async function apiPost(path, body = {}) {
        const res = await fetch(fafoEmailData.restUrl + path, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': fafoEmailData.nonce,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(body),
        });
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.error || `API error ${res.status}`);
        }
        return res.json();
    }

    async function apiPatch(path, body = {}) {
        const res = await fetch(fafoEmailData.restUrl + path, {
            method: 'PATCH',
            headers: {
                'X-WP-Nonce': fafoEmailData.nonce,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(body),
        });
        if (!res.ok) throw new Error(`API error ${res.status}`);
        return res.json();
    }

    async function apiDelete(path, params = {}) {
        const url = new URL(fafoEmailData.restUrl + path, window.location.href);
        Object.entries(params).forEach(([k, v]) => url.searchParams.set(k, v));
        const res = await fetch(url.toString(), {
            method: 'DELETE',
            headers: { 'X-WP-Nonce': fafoEmailData.nonce },
        });
        if (!res.ok) throw new Error(`API error ${res.status}`);
        return res.json();
    }

    // ============================================================
    // TOAST NOTIFICATIONS
    // ============================================================
    function toast(msg, type = 'info', duration = 3000) {
        const $container = $('#fec-toast-container');
        const $t = $(`<div class="fec-toast ${type}"><i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i> ${escHtml(msg)}</div>`);
        $container.append($t);
        setTimeout(() => $t.remove(), duration);
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function formatBytes(bytes) {
        if (!bytes) return '';
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr);
        const now = new Date();
        const diff = (now - d) / 1000;
        if (diff < 86400 && d.toDateString() === now.toDateString()) {
            return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        if (diff < 86400 * 7) {
            return d.toLocaleDateString([], { weekday: 'short' });
        }
        return d.toLocaleDateString([], { month: 'short', day: 'numeric' });
    }

    function getFileIcon(contentType, filename) {
        const ext = (filename || '').split('.').pop().toLowerCase();
        if (/^image/.test(contentType) || /^(jpg|jpeg|png|gif|webp|svg)$/.test(ext)) return 'fa-file-image';
        if (/pdf/.test(contentType) || ext === 'pdf') return 'fa-file-pdf';
        if (/word|docx?/.test(contentType) || /^docx?$/.test(ext)) return 'fa-file-word';
        if (/excel|xlsx?|csv/.test(contentType) || /^(xlsx?|csv)$/.test(ext)) return 'fa-file-excel';
        if (/zip|rar|7z|tar/.test(contentType) || /^(zip|rar|7z|tar|gz)$/.test(ext)) return 'fa-file-archive';
        if (/video/.test(contentType) || /^(mp4|mov|avi|mkv)$/.test(ext)) return 'fa-file-video';
        if (/audio/.test(contentType) || /^(mp3|wav|aac)$/.test(ext)) return 'fa-file-audio';
        return 'fa-file';
    }

    // ============================================================
    // LOAD EMAILS
    // ============================================================
    async function loadEmails() {
        const $list = $('#fec-email-list');
        $list.html('<div class="fec-loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</div>');
        state.selectedIds.clear();
        updateSelectAllCheckbox();

        try {
            const params = {
                folder:   state.folder,
                search:   state.search,
                per_page: state.perPage,
                page:     state.page,
            };
            if (state.folder === 'starred') {
                params.starred = true;
                delete params.folder;
            }

            const data = await apiGet('emails', params);
            state.counts = data.counts || {};
            updateCounts();
            renderEmailList(data.emails || []);
        } catch (e) {
            $list.html(`<div class="fec-empty"><i class="fas fa-exclamation-triangle"></i><h3>Error loading emails</h3><p>${escHtml(e.message)}</p></div>`);
        }
    }

    // ============================================================
    // RENDER EMAIL LIST
    // ============================================================
    function renderEmailList(emails) {
        const $list = $('#fec-email-list');

        if (!emails.length) {
            const labels = {
                inbox: 'No emails in your inbox', sent: 'No sent emails',
                drafts: 'No drafts', trash: 'Trash is empty',
                spam: 'No spam', starred: 'No starred emails',
            };
            $list.html(`<div class="fec-empty"><i class="fas fa-inbox"></i><h3>${labels[state.folder] || 'No emails'}</h3><p>Emails will appear here.</p></div>`);
            return;
        }

        const html = emails.map(email => {
            const isUnread  = !parseInt(email.is_read);
            const isStarred = parseInt(email.is_starred);
            const hasAtts   = parseInt(email.has_attachments);
            const sender    = email.folder === 'sent' || email.folder === 'drafts'
                ? ('To: ' + escHtml(email.to_email || ''))
                : escHtml(email.from_name || email.from_email || 'Unknown');

            return `
            <div class="fec-email-row ${isUnread ? 'unread' : ''}" data-id="${email.id}" role="row">
                <div class="fec-row-check">
                    <input type="checkbox" class="fec-row-checkbox" data-id="${email.id}" ${state.selectedIds.has(email.id) ? 'checked' : ''}>
                </div>
                <div class="fec-row-star ${isStarred ? 'starred' : ''}" title="${isStarred ? 'Unstar' : 'Star'}">
                    <i class="fa${isStarred ? 's' : 'r'} fa-star"></i>
                </div>
                <div class="fec-row-sender">${sender}</div>
                <div class="fec-row-subject">
                    ${escHtml(email.subject || '(no subject)')}
                    <span class="fec-row-snippet">${escHtml((email.body_text || '').substring(0, 80))}</span>
                </div>
                <div class="fec-row-icons">
                    ${hasAtts ? '<i class="fas fa-paperclip" title="Has attachments"></i>' : ''}
                </div>
                <div class="fec-row-date">${formatDate(email.received_at)}</div>
            </div>`;
        }).join('');

        $list.html(html);
    }

    // ============================================================
    // RENDER EMAIL VIEW
    // ============================================================
    async function openEmail(id) {
        const $pane = $('#fec-reading-pane');
        // Add .open so CSS shows the pane (display:flex)
        $pane.addClass('open').html('<div class="fec-loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</div>');
        $('#fec-main').addClass('reading');

        try {
            const email = await apiGet(`emails/${id}`);
            state.currentEmail = email;

            // Mark as read in list
            $(`[data-id="${id}"]`).removeClass('unread');
            const inboxCount = state.counts['inbox'];
            if (inboxCount && inboxCount.unread > 0) {
                inboxCount.unread--;
                updateCounts();
            }

            const avatarChar = (email.from_name || email.from_email || '?').charAt(0).toUpperCase();
            const bodyContent = email.body_html
                ? `<div class="fec-email-body">${email.body_html}</div>`
                : `<pre class="fec-email-text-body">${escHtml(email.body_text || '')}</pre>`;

            const attachmentsHtml = (email.attachments && email.attachments.length)
                ? `<div class="fec-attachments">
                    ${email.attachments.map(a => `
                    <a href="${fafoEmailData.restUrl}emails/${email.id}/attachments/${a.id}?_wpnonce=${fafoEmailData.nonce}"
                       class="fec-attachment-chip" target="_blank">
                        <i class="fas ${getFileIcon(a.content_type, a.filename)}"></i>
                        <div class="fec-attachment-info">
                            <span class="fec-attachment-name">${escHtml(a.filename)}</span>
                            <span class="fec-attachment-size">${formatBytes(a.file_size)}</span>
                        </div>
                    </a>`).join('')}
                   </div>` : '';

            $pane.html(`
            <div class="fec-email-view">
                <div class="fec-email-view-header">
                    <div class="fec-email-view-back">
                        <button class="fec-view-back-btn" id="fec-back-btn" title="Back">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div class="fec-view-actions">
                            <button class="fec-view-action-btn" data-action="reply" title="Reply"><i class="fas fa-reply"></i></button>
                            <button class="fec-view-action-btn" data-action="forward" title="Forward"><i class="fas fa-share"></i></button>
                            <button class="fec-view-action-btn" data-action="star" title="${email.is_starred ? 'Unstar' : 'Star'}">
                                <i class="fa${email.is_starred ? 's' : 'r'} fa-star" style="color:${email.is_starred ? '#F4B400' : 'inherit'};"></i>
                            </button>
                            <button class="fec-view-action-btn" data-action="trash" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            <button class="fec-view-action-btn" data-action="spam" title="Report spam"><i class="fas fa-ban"></i></button>
                            <button class="fec-view-action-btn" data-action="print" title="Print"><i class="fas fa-print"></i></button>
                        </div>
                    </div>
                </div>
                <div class="fec-email-subject">${escHtml(email.subject || '(no subject)')}</div>
                <div class="fec-email-meta">
                    <div class="fec-meta-avatar">${avatarChar}</div>
                    <div class="fec-meta-details">
                        <div class="fec-meta-from">${escHtml(email.from_name || email.from_email)}</div>
                        <div class="fec-meta-email">&lt;${escHtml(email.from_email)}&gt;</div>
                        <div class="fec-meta-to">to ${escHtml(email.to_email)}${email.cc ? ' <span>cc: ' + escHtml(email.cc) + '</span>' : ''}</div>
                    </div>
                    <div class="fec-meta-date">${email.received_at || ''}</div>
                </div>
                <div class="fec-email-body-wrap">${bodyContent}</div>
                ${attachmentsHtml}
                <div class="fec-reply-bar">
                    <button class="fec-reply-btn" data-action="reply"><i class="fas fa-reply"></i> Reply</button>
                    <button class="fec-reply-btn" data-action="forward"><i class="fas fa-share"></i> Forward</button>
                </div>
            </div>`);

        } catch (e) {
            $pane.html(`<div class="fec-empty"><i class="fas fa-exclamation-triangle"></i><h3>Error</h3><p>${escHtml(e.message)}</p></div>`);
        }
    }

    function closeEmail() {
        // Removing .open hides pane via CSS (display:none on base class)
        $('#fec-reading-pane').removeClass('open').html('');
        $('#fec-main').removeClass('reading');
        state.currentEmail = null;
    }

    // ============================================================
    // UPDATE FOLDER COUNTS IN SIDEBAR
    // ============================================================
    function updateCounts() {
        const folders = ['inbox', 'sent', 'drafts', 'trash', 'spam', 'starred'];
        folders.forEach(f => {
            const info  = state.counts[f] || {};
            const badge = $(`[data-folder="${f}"] .fec-badge`);
            if (f === 'inbox' && info.unread > 0) {
                badge.text(info.unread).addClass('unread');
            } else if (info.total > 0) {
                badge.text(info.total).removeClass('unread');
            } else {
                badge.text('');
            }
        });
    }

    function updateSelectAllCheckbox() {
        $('#fec-select-all').prop('checked', false).prop('indeterminate', false);
    }

    // ============================================================
    // COMPOSE MAIL
    // ============================================================
    function openCompose(prefill = {}) {
        const $modal = $('#fec-compose-overlay');
        $modal.find('#fec-compose-to').val(prefill.to || '');
        $modal.find('#fec-compose-cc').val(prefill.cc || '');
        $modal.find('#fec-compose-subject').val(prefill.subject || '');
        $modal.find('#fec-compose-body').html(prefill.body || '');
        state.composePendingAttachments = [];
        renderPendingAttachments();
        $modal.addClass('open');
        $modal.find('#fec-compose-to').focus();
    }

    function closeCompose() {
        $('#fec-compose-overlay').removeClass('open');
        state.composePendingAttachments = [];
        renderPendingAttachments();
    }

    function renderPendingAttachments() {
        const $wrap = $('#fec-pending-attachments');
        $wrap.html(state.composePendingAttachments.map((a, i) => `
            <div class="fec-pending-att">
                <i class="fas ${getFileIcon(a.content_type, a.filename)}"></i>
                <span>${escHtml(a.filename)}</span>
                <button type="button" class="fec-remove-att" data-index="${i}">✕</button>
            </div>`).join(''));
    }

    async function sendComposedEmail(isDraft) {
        const $modal = $('#fec-compose-overlay');
        const to      = $modal.find('#fec-compose-to').val().trim();
        const cc      = $modal.find('#fec-compose-cc').val().trim();
        const subject = $modal.find('#fec-compose-subject').val().trim();
        const bodyEl  = $modal.find('#fec-compose-body')[0];
        const body_html = bodyEl.innerHTML;
        const body_text = bodyEl.innerText;

        if (!isDraft && !to) { toast('Please enter a recipient.', 'error'); return; }

        const $btn = isDraft ? $modal.find('.fec-draft-btn') : $modal.find('.fec-send-btn');
        $btn.prop('disabled', true).text(isDraft ? 'Saving...' : 'Sending...');

        try {
            await apiPost('emails', {
                to, cc, subject, body_html, body_text,
                attachment_ids: state.composePendingAttachments.map(a => a.id),
                draft: isDraft,
            });
            closeCompose();
            toast(isDraft ? 'Draft saved.' : 'Email sent!', 'success');
            if (!isDraft && state.folder === 'sent') loadEmails();
            if (isDraft && state.folder === 'drafts') loadEmails();
        } catch (e) {
            toast('Failed: ' + e.message, 'error');
        } finally {
            $btn.prop('disabled', false).text(isDraft ? 'Save Draft' : 'Send');
        }
    }

    // ============================================================
    // FORWARD MODAL
    // ============================================================
    function openForward(emailId) {
        $('#fec-forward-email-id').val(emailId);
        $('#fec-forward-to').val('');
        $('#fec-forward-note').val('');
        $('#fec-forward-modal').addClass('open');
        $('#fec-forward-to').focus();
    }

    async function sendForward() {
        const id   = $('#fec-forward-email-id').val();
        const to   = $('#fec-forward-to').val().trim();
        const note = $('#fec-forward-note').val().trim();

        if (!to) { toast('Enter a forward-to address.', 'error'); return; }

        const $btn = $('#fec-forward-modal .fec-modal-btn.primary');
        $btn.prop('disabled', true).text('Forwarding...');

        try {
            await apiPost(`emails/${id}/forward`, { to, note });
            $('#fec-forward-modal').removeClass('open');
            toast('Email forwarded!', 'success');
        } catch (e) {
            toast('Forward failed: ' + e.message, 'error');
        } finally {
            $btn.prop('disabled', false).text('Forward');
        }
    }

    // ============================================================
    // BULK ACTIONS
    // ============================================================
    async function bulkAction(action) {
        const ids = Array.from(state.selectedIds);
        if (!ids.length) { toast('Select emails first.', 'error'); return; }

        try {
            await apiPost('emails/bulk', { ids, action });
            toast(`Done (${ids.length} emails).`, 'success');
            state.selectedIds.clear();
            loadEmails();
        } catch (e) {
            toast('Error: ' + e.message, 'error');
        }
    }

    // ============================================================
    // SINGLE QUICK ACTIONS
    // ============================================================
    async function emailAction(id, action) {
        try {
            switch (action) {
                case 'trash':
                    await apiDelete(`emails/${id}`);
                    toast('Moved to trash.');
                    break;
                case 'spam':
                    await apiPatch(`emails/${id}`, { folder: 'spam' });
                    toast('Marked as spam.');
                    break;
                case 'star': {
                    const isStarred = state.currentEmail && state.currentEmail.is_starred;
                    await apiPatch(`emails/${id}`, { is_starred: isStarred ? 0 : 1 });
                    toast(isStarred ? 'Unstarred.' : 'Starred!');
                    if (state.currentEmail) state.currentEmail.is_starred = isStarred ? 0 : 1;
                    break;
                }
                case 'reply':
                    if (state.currentEmail) {
                        openCompose({
                            to:      state.currentEmail.from_email,
                            subject: 'Re: ' + state.currentEmail.subject,
                            body:    `<br><br><blockquote style="border-left:3px solid #ccc;padding-left:12px;color:#666;margin:0;font-size:13px;"><strong>On ${state.currentEmail.received_at}, ${escHtml(state.currentEmail.from_name || state.currentEmail.from_email)} wrote:</strong><br>${state.currentEmail.body_html || escHtml(state.currentEmail.body_text || '')}</blockquote>`,
                        });
                    }
                    return;
                case 'forward':
                    if (state.currentEmail) openForward(state.currentEmail.id);
                    return;
                case 'print':
                    window.print();
                    return;
            }
            closeEmail();
            loadEmails();
        } catch (e) {
            toast('Action failed: ' + e.message, 'error');
        }
    }

    // ============================================================
    // FILE UPLOAD
    // ============================================================
    async function uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('nonce', fafoEmailData.ajaxNonce);

        const res = await fetch(fafoEmailData.uploadUrl, { method: 'POST', body: formData });
        if (!res.ok) throw new Error('Upload failed');
        const data = await res.json();
        if (!data.success) throw new Error(data.data?.message || 'Upload error');
        return data.data;
    }

    // ============================================================
    // EVENT BINDINGS
    // ============================================================
    $(document).ready(function () {
        // (initial load is called after setting the correct folder at bottom of ready block)

        // --- NAV: folder switching ---
        $(document).on('click', '.fec-nav-item[data-folder]', function (e) {
            e.preventDefault();
            const folder = $(this).data('folder');
            state.folder = folder;
            state.page   = 1;
            state.search = '';
            $('#fec-search-input').val('');
            $('.fec-nav-item').removeClass('active');
            $(this).addClass('active');
            closeEmail();
            loadEmails();
        });

        // --- SEARCH ---
        let searchTimer;
        $('#fec-search-input').on('input', function () {
            clearTimeout(searchTimer);
            state.search = $(this).val().trim();
            searchTimer = setTimeout(() => { state.page = 1; loadEmails(); }, 400);
        });

        // --- COMPOSE button ---
        $(document).on('click', '#fec-compose-btn', function () {
            openCompose();
        });

        // --- CLOSE COMPOSE ---
        $(document).on('click', '.fec-compose-close', closeCompose);

        // --- SEND EMAIL ---
        $(document).on('click', '#fec-send-btn', function () {
            sendComposedEmail(false);
        });

        // --- SAVE DRAFT ---
        $(document).on('click', '#fec-draft-btn', function () {
            sendComposedEmail(true);
        });

        // --- ATTACH FILE ---
        $(document).on('click', '#fec-attach-btn', function () {
            $('#fec-file-input').click();
        });

        $(document).on('change', '#fec-file-input', async function () {
            const files = this.files;
            for (const file of files) {
                try {
                    const data = await uploadFile(file);
                    state.composePendingAttachments.push({
                        id:           data.id || 0,
                        filename:     data.filename,
                        content_type: data.content_type,
                        stored_path:  data.path,
                    });
                    renderPendingAttachments();
                    toast('Attached: ' + data.filename, 'success', 2000);
                } catch (e) {
                    toast('Upload failed: ' + e.message, 'error');
                }
            }
            $(this).val('');
        });

        $(document).on('click', '.fec-remove-att', function () {
            const idx = parseInt($(this).data('index'));
            state.composePendingAttachments.splice(idx, 1);
            renderPendingAttachments();
        });

        // --- EMAIL ROW CLICK (open) ---
        $(document).on('click', '.fec-email-row', function (e) {
            if ($(e.target).closest('.fec-row-check, .fec-row-star').length) return;
            const id = parseInt($(this).data('id'));
            openEmail(id);
        });

        // --- CHECKBOX ---
        $(document).on('change', '.fec-row-checkbox', function () {
            const id = parseInt($(this).data('id'));
            if ($(this).is(':checked')) {
                state.selectedIds.add(id);
                $(this).closest('.fec-email-row').addClass('selected');
            } else {
                state.selectedIds.delete(id);
                $(this).closest('.fec-email-row').removeClass('selected');
            }

            const total    = $('.fec-row-checkbox').length;
            const checked  = $('.fec-row-checkbox:checked').length;
            const $all     = $('#fec-select-all');
            $all.prop('indeterminate', checked > 0 && checked < total)
                .prop('checked', checked === total && total > 0);
        });

        // --- SELECT ALL ---
        $(document).on('change', '#fec-select-all', function () {
            const checked = $(this).is(':checked');
            $('.fec-row-checkbox').prop('checked', checked).trigger('change');
        });

        // --- STAR ---
        $(document).on('click', '.fec-row-star', async function (e) {
            e.stopPropagation();
            const $row     = $(this).closest('.fec-email-row');
            const id       = parseInt($row.data('id'));
            const starred  = $(this).hasClass('starred');
            $(this).toggleClass('starred').find('i').toggleClass('fas', !starred).toggleClass('far', starred);
            await apiPatch(`emails/${id}`, { is_starred: starred ? 0 : 1 }).catch(() => {});
        });

        // --- BACK BUTTON ---
        $(document).on('click', '#fec-back-btn', closeEmail);

        // --- EMAIL VIEW ACTIONS ---
        $(document).on('click', '.fec-view-action-btn', function () {
            const action = $(this).data('action');
            if (state.currentEmail) emailAction(state.currentEmail.id, action);
        });

        $(document).on('click', '.fec-reply-btn', function () {
            const action = $(this).data('action');
            if (state.currentEmail) emailAction(state.currentEmail.id, action);
        });

        // --- BULK TOOLBAR ---
        $(document).on('click', '#fec-bulk-trash',      () => bulkAction('trash'));
        $(document).on('click', '#fec-bulk-read',       () => bulkAction('mark_read'));
        $(document).on('click', '#fec-bulk-unread',     () => bulkAction('mark_unread'));
        $(document).on('click', '#fec-bulk-spam',       () => bulkAction('spam'));
        $(document).on('click', '#fec-bulk-not-spam',   () => bulkAction('inbox'));
        $(document).on('click', '#fec-bulk-delete',     () => bulkAction('delete'));
        $(document).on('click', '#fec-refresh-btn',     () => loadEmails());

        // --- FORWARD MODAL ---
        $(document).on('click', '#fec-forward-send', sendForward);
        $(document).on('click', '#fec-forward-cancel', function () {
            $('#fec-forward-modal').removeClass('open');
        });

        // --- KEYBOARD SHORTCUTS ---
        $(document).on('keydown', function (e) {
            if (['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName) || e.target.contentEditable === 'true') return;

            switch (e.key) {
                case 'c': case 'C':
                    if (!e.ctrlKey && !e.metaKey) openCompose();
                    break;
                case 'Escape':
                    if ($('#fec-compose-overlay').hasClass('open')) closeCompose();
                    else if ($('#fec-forward-modal').hasClass('open')) $('#fec-forward-modal').removeClass('open');
                    else if (state.currentEmail) closeEmail();
                    break;
                case 'r': case 'R':
                    if (state.currentEmail && !e.ctrlKey) emailAction(state.currentEmail.id, 'reply');
                    break;
                case 'f': case 'F':
                    if (state.currentEmail && !e.ctrlKey) emailAction(state.currentEmail.id, 'forward');
                    break;
            }
        });

        // --- CTRL+ENTER to send compose ---
        $(document).on('keydown', '#fec-compose-body, #fec-compose-to, #fec-compose-subject', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') sendComposedEmail(false);
        });

        // --- DRAG OVER overlay for file drop ---
        $(document).on('dragover', '#fec-compose-overlay', function(e) { e.preventDefault(); });
        $(document).on('drop', '#fec-compose-overlay', async function(e) {
            e.preventDefault();
            const files = e.originalEvent.dataTransfer.files;
            for (const file of files) {
                try {
                    const data = await uploadFile(file);
                    state.composePendingAttachments.push({ id: data.id || 0, filename: data.filename, content_type: data.content_type });
                    renderPendingAttachments();
                } catch {}
            }
        });

        // Highlight the correct nav item based on the folder passed from PHP
        const initFolder = window.fafoInitialFolder || 'inbox';
        state.folder = initFolder;
        $('.fec-nav-item').removeClass('active');
        $(`.fec-nav-item[data-folder="${initFolder}"]`).addClass('active');

        // Load initial folder
        loadEmails();
    });

})(jQuery);
