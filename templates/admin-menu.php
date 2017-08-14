<?php
/**
 * Contexts:
 */
?>
<div class="wrap">
    <h2><?php _e( 'Backbone.js &amp; WP Custom Post', 'bb-cpt' ); ?></h2>
</div>

<h3><?php _e( 'Your Posts', 'bb-cpt' ); ?></h3>
<article id="your-posts">
    <table class="widefat">
        <thead>
        <tr>
            <td><?php _e( 'ID', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Author', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Date', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Title', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Content', 'bb-cpt' ); ?></td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</article>
<hr/>

<h3><?php _e( 'Your Pages', 'bb-cpt' ); ?></h3>
<article id="your-pages">
    <table class="widefat">
        <thead>
        <tr>
            <td><?php _e( 'ID', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Author', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Date', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Title', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Content', 'bb-cpt' ); ?></td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</article>
<hr/>

<h3><?php _e( 'Your Custom Posts', 'bb-cpt' ); ?></h3>
<article id="your-custom-posts">
    <table class="widefat">
        <thead>
        <tr>
            <td><?php _e( 'ID', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Author', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Date', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Title', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Content', 'bb-cpt' ); ?></td>
            <td><?php _e( 'Telephone', 'bb-cpt' ); ?></td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</article>
<hr/>

<div id="bb-cpt-control">
    <div id="bb-cpt-edit">
        <h3><?php _e( 'Quick Post', 'bb-cpt' ); ?></h3>
        <ul class="quick-post-list">
            <li>
                <label class="quick-post-label" for="bb-cpt-post-type"><?php _e( 'Post Type', 'bb-cpt' ); ?></label>
                <select id="bb-cpt-post-type">
                    <optgroup label="<?php _e( 'Built-in Types', 'bb-cpt' ); ?>">
                        <option value="post" selected><?php _e( 'Post', 'bb-cpt' ); ?></option>
                        <option value="page"><?php _e( 'Page', 'bb-cpt' ); ?></option>
                    </optgroup>
                    <optgroup label="<?php _e( 'Custom Post Type', 'bb-cpt' ); ?>">
                        <option value="bb-cpt"><?php _e( 'bb-cpt', 'bb-cpt' ); ?></option>
                    </optgroup>
                </select>
            </li>
            <li>
                <label class="quick-post-label"><?php _e( 'Edit Mode', 'bb-cpt' ); ?></label>
                <ul id="bb-cpt-edit-option-list">
                    <li>
                        <label for="bb-cpt-edit-mode-new">
                            <input id="bb-cpt-edit-mode-new" type="radio" name="bb-cpt-edit-mode" value="new"
                                   data-mode="add" checked/>
							<?php _e( 'Add a new post', 'bb-cpt' ); ?>
                        </label>
                    </li>
                    <li>
                        <label for="bb-cpt-edit-mode-modify">
                            <input id="bb-cpt-edit-mode-modify" type="radio" name="bb-cpt-edit-mode" value="modify"
                                   data-mode="modify"/>
							<?php _e( 'Modify an existing post', 'bb-cpt' ); ?>
                        </label>
                    </li>
                    <li>
                        <label for="bb-cpt-edit-mode-delete">
                            <input id="bb-cpt-edit-mode-delete" type="radio" name="bb-cpt-edit-mode" value="delete"
                                   data-mode="delete"/>
							<?php _e( 'Delete a post', 'bb-cpt' ); ?>
                        </label>
                    </li>
                    <li id="li-bb-cpt-edit-target">
                        <label for="bb-cpt-edit-target">
							<?php _e( 'Choose a target post', 'bb-cpt' ); ?>
                            <select id="bb-cpt-edit-target"></select>
                        </label>
                    </li>
                </ul>
                <div class="clear"></div>
            </li>
            <li>
                <label class="quick-post-label" for="bb-cpt-edit-author"><?php _e( 'Author', 'bb-cpt' ); ?></label>
				<?php wp_dropdown_users( array( 'id' => 'bb-cpt-edit-author' ) ); ?>
            <li>
                <label class="quick-post-label" for="bb-cpt-edit-title"><?php _e( 'Title', 'bb-cpt' ); ?></label>
                <input id="bb-cpt-edit-title" type="text" class="text large-text" value=""/>
            </li>
            <li>
                <label class="quick-post-label" for="bb-cpt-edit-content"><?php _e( 'Content', 'bb-cpt' ); ?></label>
                <textarea id="bb-cpt-edit-content" rows="5"></textarea>
            </li>
            <li>
                <label class="quick-post-label" for="bb-cpt-meta-tel"><?php _e( 'Telephone', 'bb-cpt' ); ?></label>
                <input id="bb-cpt-meta-tel" type="text" class="text" value="" />
            </li>
        </ul>
        <button id="bb-cpt-edit-button" type="button" class="button button-primary">
			<?php _e( 'Submit', 'bb-cpt' ); ?>
        </button>
    </div>
</div>

<script type="text/template" id="tmpl-table-template">
    <# _.each(data, function(model) { #>
        <tr>
            <td> {{ model.attributes.id }}</td>
            <td> {{ model.attributes.author }}</td>
            <td> {{ model.attributes.date }}</td>
            <td> {{{ model.attributes.title.rendered }}}</td>
            <td> {{{ model.attributes.content.rendered }}}</td>
            <td>
                <# if(model.attributes.hasOwnProperty('tel')) { #>
                    {{ model.attributes.tel }}
                <# } #>
            </td>
        </tr>
        <# }); #>
</script>
