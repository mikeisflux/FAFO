<?php
/**
 * FAFO Email Client - Main Admin Page (Gmail-like UI)
 */
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) wp_die('Forbidden');

// Ensure DB tables exist
FAFO_Email_Database::install();

// Determine active folder from page slug
$page_slug = $_GET['page'] ?? 'fafo-email';
$folder_map = [
    'fafo-email'          => 'inbox',
    'fafo-email-sent'     => 'sent',
    'fafo-email-drafts'   => 'drafts',
    'fafo-email-starred'  => 'starred',
    'fafo-email-trash'    => 'trash',
    'fafo-email-spam'     => 'spam',
];
$active_folder = $folder_map[ $page_slug ] ?? 'inbox';
?>
<!-- Remove default WP padding for this page -->
<style>
    #wpcontent { padding-left: 0 !important; }
    #wpbody-content { padding-bottom: 0 !important; }
</style>

<div class="fafo-email-wrap">

    <!-- ============ TOP TOOLBAR ============ -->
    <div class="fec-toolbar">
        <div class="fec-logo">F<span>A</span>FO <span style="font-size:14px;color:#5f6368;font-weight:400;">Mail</span></div>
        <div class="fec-search-wrap">
            <i class="fas fa-search fec-search-icon"></i>
            <input type="search" id="fec-search-input" class="fec-search" placeholder="Search emails...">
        </div>
        <div class="fec-toolbar-right">
            <button class="fec-list-toolbar .fec-btn" id="fec-refresh-btn" title="Refresh" style="background:transparent;border:none;cursor:pointer;padding:8px;border-radius:50%;font-size:16px;color:#5f6368;transition:background .1s;">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="<?php echo esc_url( admin_url('admin.php?page=fafo-email-settings') ); ?>" title="Settings" style="background:transparent;border:none;cursor:pointer;padding:8px;border-radius:50%;font-size:16px;color:#5f6368;text-decoration:none;display:flex;align-items:center;width:36px;height:36px;justify-content:center;transition:background .1s;" onmouseover="this.style.background='#e2e6ea'" onmouseout="this.style.background='transparent'">
                <i class="fas fa-cog"></i>
            </a>
            <?php $user = wp_get_current_user(); ?>
            <div style="width:36px;height:36px;border-radius:50%;background:#002868;color:#FFD700;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;cursor:default;" title="<?php echo esc_attr($user->display_name); ?>">
                <?php echo esc_html( strtoupper( substr($user->display_name, 0, 1) ) ); ?>
            </div>
        </div>
    </div>

    <!-- ============ MAIN LAYOUT ============ -->
    <div class="fec-layout">

        <!-- ---- SIDEBAR ---- -->
        <nav class="fec-sidebar">
            <button class="fec-compose-btn" id="fec-compose-btn">
                <i class="fas fa-pen"></i>
                <span>Compose</span>
            </button>

            <a href="<?php echo esc_url( admin_url('admin.php?page=fafo-email') ); ?>"
               class="fec-nav-item <?php echo $active_folder === 'inbox' ? 'active' : ''; ?>"
               data-folder="inbox">
                <i class="fas fa-inbox"></i>
                <span>Inbox</span>
                <span class="fec-badge" data-folder-badge="inbox"></span>
            </a>

            <a href="<?php echo esc_url( admin_url('admin.php?page=fafo-email-starred') ); ?>"
               class="fec-nav-item <?php echo $active_folder === 'starred' ? 'active' : ''; ?>"
               data-folder="starred">
                <i class="far fa-star"></i>
                <span>Starred</span>
                <span class="fec-badge" data-folder-badge="starred"></span>
            </a>

            <a href="<?php echo esc_url( admin_url('admin.php?page=fafo-email-sent') ); ?>"
               class="fec-nav-item <?php echo $active_folder === 'sent' ? 'active' : ''; ?>"
               data-folder="sent">
                <i class="fas fa-paper-plane"></i>
                <span>Sent</span>
                <span class="fec-badge" data-folder-badge="sent"></span>
            </a>

            <a href="<?php echo esc_url( admin_url('admin.php?page=fafo-email-drafts') ); ?>"
               class="fec-nav-item <?php echo $active_folder === 'drafts' ? 'active' : ''; ?>"
               data-folder="drafts">
                <i class="far fa-file-alt"></i>
                <span>Drafts</span>
                <span class="fec-badge" data-folder-badge="drafts"></span>
            </a>

            <div class="fec-nav-separator"></div>

            <a href="<?php echo esc_url( admin_url('admin.php?page=fafo-email-spam') ); ?>"
               class="fec-nav-item <?php echo $active_folder === 'spam' ? 'active' : ''; ?>"
               data-folder="spam">
                <i class="fas fa-ban"></i>
                <span>Spam</span>
                <span class="fec-badge" data-folder-badge="spam"></span>
            </a>

            <a href="<?php echo esc_url( admin_url('admin.php?page=fafo-email-trash') ); ?>"
               class="fec-nav-item <?php echo $active_folder === 'trash' ? 'active' : ''; ?>"
               data-folder="trash">
                <i class="fas fa-trash-alt"></i>
                <span>Trash</span>
                <span class="fec-badge" data-folder-badge="trash"></span>
            </a>

            <div class="fec-nav-separator"></div>

            <!-- Webhook status indicator -->
            <div style="padding:8px 16px 0 26px;">
                <div style="font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#9aa0a6;margin-bottom:6px;">Inbound</div>
                <div style="font-size:12px;color:#5f6368;line-height:1.5;">
                    <i class="fas fa-circle" style="color:#34a853;font-size:8px;"></i>
                    <span style="margin-left:4px;">inbound.foramericafirstonly.com</span>
                </div>
                <div style="font-size:11px;color:#9aa0a6;margin-top:4px;">Webhook active</div>
            </div>
        </nav><!-- /.fec-sidebar -->

        <!-- ---- MAIN EMAIL PANEL ---- -->
        <div class="fec-main" id="fec-main">

            <!-- Email list toolbar -->
            <div class="fec-list-toolbar">
                <div class="fec-select-all">
                    <input type="checkbox" id="fec-select-all" title="Select all">
                </div>

                <button class="fec-btn" id="fec-refresh-list" title="Refresh"><i class="fas fa-sync-alt"></i></button>

                <!-- Bulk actions (visible when items selected) -->
                <button class="fec-btn text-btn" id="fec-bulk-trash" title="Delete selected"><i class="fas fa-trash-alt"></i></button>
                <button class="fec-btn text-btn" id="fec-bulk-read" title="Mark read"><i class="fas fa-envelope-open"></i></button>
                <button class="fec-btn text-btn" id="fec-bulk-unread" title="Mark unread"><i class="fas fa-envelope"></i></button>
                <button class="fec-btn text-btn" id="fec-bulk-spam" title="Report spam"><i class="fas fa-ban"></i></button>
                <?php if ( $active_folder === 'spam' || $active_folder === 'trash' ) : ?>
                <button class="fec-btn text-btn" id="fec-bulk-not-spam" title="Not spam / restore"><i class="fas fa-inbox"></i></button>
                <button class="fec-btn text-btn" id="fec-bulk-delete" title="Delete permanently" style="color:#c62828;"><i class="fas fa-times-circle"></i></button>
                <?php endif; ?>

                <div class="fec-pagination-info">
                    <span id="fec-count-info"></span>
                    <button class="fec-btn" id="fec-prev-page" title="Previous page" disabled><i class="fas fa-chevron-left"></i></button>
                    <button class="fec-btn" id="fec-next-page" title="Next page"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Email list -->
            <div id="fec-email-list" class="fec-email-list">
                <div class="fec-loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</div>
            </div>

            <!-- Reading pane (overlays list) -->
            <div id="fec-reading-pane" class="fec-reading-pane" style="display:none;"></div>

        </div><!-- /.fec-main -->

    </div><!-- /.fec-layout -->

</div><!-- /.fafo-email-wrap -->

<!-- ============ COMPOSE OVERLAY ============ -->
<div id="fec-compose-overlay" class="fec-compose-overlay" role="dialog" aria-label="Compose email">
    <div class="fec-compose-modal">
        <div class="fec-compose-header">
            <h4>New Message</h4>
            <button class="fec-compose-close" title="Close">✕</button>
        </div>
        <div class="fec-compose-fields">
            <div class="fec-compose-field">
                <label for="fec-compose-to">To</label>
                <input type="email" id="fec-compose-to" name="to" placeholder="Recipients" multiple>
            </div>
            <div class="fec-compose-field" id="fec-compose-cc-row" style="display:none;">
                <label for="fec-compose-cc">Cc</label>
                <input type="email" id="fec-compose-cc" name="cc" placeholder="Cc" multiple>
            </div>
            <div class="fec-compose-field" id="fec-compose-bcc-row" style="display:none;">
                <label for="fec-compose-bcc">Bcc</label>
                <input type="email" id="fec-compose-bcc" name="bcc" placeholder="Bcc" multiple>
            </div>
            <div style="padding:4px 16px;display:flex;gap:8px;">
                <button type="button" style="font-size:12px;background:none;border:none;color:#1a73e8;cursor:pointer;" onclick="$('#fec-compose-cc-row').toggle()">Cc</button>
                <button type="button" style="font-size:12px;background:none;border:none;color:#1a73e8;cursor:pointer;" onclick="$('#fec-compose-bcc-row').toggle()">Bcc</button>
            </div>
        </div>
        <div class="fec-compose-subject-row">
            <input type="text" id="fec-compose-subject" placeholder="Subject">
        </div>
        <div id="fec-compose-body" class="fec-compose-body" contenteditable="true" data-placeholder="Compose email..." role="textbox" aria-multiline="true"></div>
        <div id="fec-pending-attachments" class="fec-pending-attachments"></div>
        <div class="fec-compose-footer">
            <button class="fec-send-btn" id="fec-send-btn"><i class="fas fa-paper-plane"></i> Send</button>
            <button class="fec-attach-btn" id="fec-attach-btn" title="Attach files"><i class="fas fa-paperclip"></i></button>
            <button class="fec-draft-btn" id="fec-draft-btn">Save Draft</button>
            <input type="file" id="fec-file-input" multiple style="display:none;">
        </div>
    </div>
</div>

<!-- ============ FORWARD MODAL ============ -->
<div id="fec-forward-modal" class="fec-forward-modal" role="dialog" aria-label="Forward email">
    <div class="fec-forward-inner">
        <h3><i class="fas fa-share" style="color:#002868;margin-right:8px;"></i>Forward Email</h3>
        <input type="hidden" id="fec-forward-email-id">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Forward to:</label>
            <input type="email" id="fec-forward-to" placeholder="recipient@example.com" multiple>
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Add a note (optional):</label>
            <textarea id="fec-forward-note" placeholder="Add a note above the forwarded message..."></textarea>
        </div>
        <div class="fec-forward-actions">
            <button type="button" class="fec-modal-btn" id="fec-forward-cancel">Cancel</button>
            <button type="button" class="fec-modal-btn primary" id="fec-forward-send">
                <i class="fas fa-share"></i> Forward
            </button>
        </div>
    </div>
</div>

<!-- Toast container -->
<div id="fec-toast-container" class="fec-toast-container"></div>

<script>
// Initialize with correct folder from PHP
if (typeof window.fafoEmailData !== 'undefined') {
    // Override default folder based on current page
    const phpFolder = '<?php echo esc_js($active_folder); ?>';
    document.addEventListener('DOMContentLoaded', function() {
        if (window.fafoEmailState) {
            window.fafoEmailState.folder = phpFolder;
        }
        // Highlight correct nav item
        document.querySelectorAll('.fec-nav-item').forEach(el => {
            el.classList.toggle('active', el.dataset.folder === phpFolder);
        });
    });
}

// Reading pane show/hide
const readingPane = document.getElementById('fec-reading-pane');
if (readingPane) {
    readingPane.style.display = '';
    readingPane.style.position = 'absolute';
}
</script>
