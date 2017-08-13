<?php
/**
 * Contexts:
 */
?>
<div class="wrap">
    <h2><?php _e( 'Backbone.js &amp; WP Custom Post', 'bbcpt' ); ?></h2>
</div>

<h3><?php _e( 'Your Posts', 'bbcpt' ); ?></h3>
<article id="your-posts">
    <table class="widefat">
        <thead>
        <tr>
            <td><?php _e( 'ID', 'bbcpt' ); ?></td>
            <td><?php _e( 'Author', 'bbcpt' ); ?></td>
            <td><?php _e( 'Date', 'bbcpt' ); ?></td>
            <td><?php _e( 'Title', 'bbcpt' ); ?></td>
            <td><?php _e( 'Content', 'bbcpt' ); ?></td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</article>
<hr/>

<h3><?php _e( 'Your Pages', 'bbcpt' ); ?></h3>
<article id="your-pages">
    <table class="widefat">
        <thead>
        <tr>
            <td><?php _e( 'ID', 'bbcpt' ); ?></td>
            <td><?php _e( 'Author', 'bbcpt' ); ?></td>
            <td><?php _e( 'Date', 'bbcpt' ); ?></td>
            <td><?php _e( 'Title', 'bbcpt' ); ?></td>
            <td><?php _e( 'Content', 'bbcpt' ); ?></td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</article>
<hr/>

<h3><?php _e( 'Your Custom Posts', 'bbcpt' ); ?></h3>
<article id="your-custom-posts">
    <table class="widefat">
        <thead>
        <tr>
            <td><?php _e( 'ID', 'bbcpt' ); ?></td>
            <td><?php _e( 'Author', 'bbcpt' ); ?></td>
            <td><?php _e( 'Date', 'bbcpt' ); ?></td>
            <td><?php _e( 'Title', 'bbcpt' ); ?></td>
            <td><?php _e( 'Content', 'bbcpt' ); ?></td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</article>
<hr/>

<div id="bbcpt-control">
    <div id="bbcpt-edit">
        <h3><?php _e( 'Quick Post', 'bbcpt' ); ?></h3>
        <ul class="quick-post-list">
            <li>
                <label class="quick-post-label" for="bbcpt-edit-type"><?php _e( 'Post Type', 'bbcpt' ); ?></label>
                <select id="bbcpt-edit-type">
                    <optgroup label="<?php _e('Built-in Types', 'bbcpt'); ?>">
                        <option value="post" selected><?php _e('Post', 'bbcpt'); ?></option>
                        <option value="page"><?php _e('Page', 'bbcpt'); ?></option>
                    </optgroup>
                    <optgroup label="<?php _e('Custom Post Type', 'bbcpt'); ?>">
                        <option value="bbcpt"><?php _e('bbcpt', 'bbcpt'); ?></option>
                    </optgroup>
                </select>
            </li>
            <li>
                <label class="quick-post-label"><?php _e('Edit Mode', 'bbcpt'); ?></label>
                <ul id="bbcpt-edit-option-list">
                    <li>
                        <label for="bbcpt-edit-mode-new">
                            <input id="bbcpt-edit-mode-new" type="radio" name="bbcpt-edit-mode" value="new" data-mode="add" checked />
		                    <?php _e('Add a new post', 'bbcpt'); ?>
                        </label>
                    </li>
                    <li>
                        <label for="bbcpt-edit-mode-modify">
                            <input id="bbcpt-edit-mode-modify" type="radio" name="bbcpt-edit-mode" value="modify" data-mode="modify" />
		                    <?php _e('Modify an existing post', 'bbcpt'); ?>
                        </label>
                    </li>
                    <li>
                        <label for="bbcpt-edit-mode-delete">
                            <input id="bbcpt-edit-mode-delete" type="radio" name="bbcpt-edit-mode" value="delete" data-mode="delete" />
		                    <?php _e('Delete a post', 'bbcpt'); ?>
                        </label>
                    </li>
                    <li id="li-bbcpt-edit-target">
                        <label for="bbcpt-edit-target">
		                    <?php _e('Choose a target post', 'bbcpt'); ?>
                            <select id="bbcpt-edit-target"></select>
                        </label>
                    </li>
                </ul>
                <div class="clear"></div>
            </li>
            <li>
                <label class="quick-post-label" for="bbcpt-edit-author"><?php _e( 'Author', 'bbcpt' ); ?></label>
		        <?php wp_dropdown_users( array('id' => 'bbcpt-edit-author')); ?>
            <li>
                <label class="quick-post-label" for="bbcpt-edit-title"><?php _e( 'Title', 'bbcpt' ); ?></label>
                <input id="bbcpt-edit-title" type="text" class="text large-text" value=""/>
            </li>
            <li>
                <label class="quick-post-label" for="bbcpt-edit-content"><?php _e( 'Content', 'bbcpt' ); ?></label>
                <textarea id="bbcpt-edit-content" rows="5"></textarea>
            </li>
        </ul>
        <button id="bbcpt-edit-button" type="button" class="button button-primary">
            <?php _e('Post Now', 'bbcpt'); ?>
        </button>
    </div>
</div>

<script type="text/template" id="tmpl-table-template">
    <# _.each(data, function(model) { #>
        <tr>
            <td> {{ model.attributes.id }} </td>
            <td> {{ model.attributes.author }} </td>
            <td> {{ model.attributes.date }}</td>
            <td> {{{ model.attributes.title.rendered }}}</td>
            <td> {{{ model.attributes.content.rendered }}}</td>
        </tr>
    <# }); #>
</script>
