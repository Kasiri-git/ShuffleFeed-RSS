<?php
/*
Plugin Name: ShuffleFeed RSS
Description: ランダムな記事でRSSフィードを生成するプラグイン
Version: 1.0.0
Author: kasiri
*/

// メニューページを追加
function shuffle_feed_settings_page() {
    add_menu_page(
        'ShuffleFeed Settings', // ページタイトル
        'ShuffleFeed Settings', // メニュータイトル
        'manage_options', // 管理者のみがアクセス可能
        'shuffle-feed-settings', // ページスラッグ
        'shuffle_feed_settings_page_content', // ページコンテンツの表示に使用するコールバック関数
        'dashicons-admin-generic', // アイコン
        6 // メニューの位置
    );
}
add_action('admin_menu', 'shuffle_feed_settings_page');

// メニューページの内容を出力
function shuffle_feed_settings_page_content() {
    // POSTされたデータを処理
    if (isset($_POST['shuffle_feed_post_count'])) {
        // フィードで配信する件数を更新
        update_option('shuffle_feed_post_count', intval($_POST['shuffle_feed_post_count']));
        echo '<div class="updated"><p>フィードで配信する記事数が更新されました。</p></div>';
    }

    // 現在の設定を取得
    $current_post_count = get_option('shuffle_feed_post_count', SHUFFLE_FEED_DEFAULT_POST_COUNT);
    ?>
    <div class="wrap">
        <h2>ShuffleFeed Settings</h2>
        
        <p>This plugin generates a custom RSS feed that delivers posts in random order including featured images. You can specify the number of posts to be included in the feed below.</p>
        <p>To view the random feed in the admin panel, use the following URL: <strong><?php echo esc_url(site_url('?feed=shuffle')); ?></strong></p>
        
        <form method="post" action="">
            <label for="shuffle_feed_post_count">フィードで配信する記事数:</label>
            <input type="number" id="shuffle_feed_post_count" name="shuffle_feed_post_count" min="1" value="<?php echo esc_attr($current_post_count); ?>">
            <input type="submit" class="button-primary" value="保存">
        </form>
    </div>
    <?php
}

// フィードで配信する件数のデフォルト値
define('SHUFFLE_FEED_DEFAULT_POST_COUNT', 10);

// Add custom feed
function add_shuffle_feed() {
    add_feed('shuffle', 'shuffle_feed_callback');
}
add_action('init', 'add_shuffle_feed');

// Callback function for custom feed
function shuffle_feed_callback() {
    // プラグイン内のテンプレートファイルパスを取得
    $template_path = plugin_dir_path(__FILE__) . 'feed-rand.php';

    // テンプレートファイルが存在するか確認
    if (file_exists($template_path)) {
        // テンプレートファイルを読み込む
        load_template($template_path);
    } else {
        // テンプレートファイルが存在しない場合はエラーメッセージを表示
        status_header(404);
        nocache_headers();
        echo '404 Not Found';
    }
}
