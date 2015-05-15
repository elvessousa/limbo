<!-- -----------------------------------------------------------------------

	limbo OPTIONS FIELDS
    Prints the options in the limbo options screen.
    Make sure you know what you're doing before altering this code.

    - Header
    - Options
    - Admin
    - Frontend
    - Form submission fields
    - Options from the database

------------------------------------------------------------------------ -->

<!-- Header -->
<header class="options-title">
    <h1>Limbo options</h1>
    <p>Site: <?php echo get_bloginfo('name') ?></p>
</header>

<!-- Options -->
<section ng-app="ess-limbo-options" class="limbo-options" action="">
    <fieldset ng-controller="limbosCtrl as options">

        <!-- Admin -->
        <dl class="form-page" >
            <h4><?php _e('Admin', 'ess-limbo') ?></h4>

            <dt><label for="sidebars"><?php _e('Sidebars', 'ess-limbo') ?></label></dt>
            <dd><input id="sidebars" type="number" ng-model="options.sidebars" placeholder="Sidebars"></dd>
        </dl>

        <!-- Frontend -->
        <dl class="form-page">
            <h4><?php _e('Frontend', 'ess-limbo') ?></h4>

            <dt><label for="class"><?php _e('Item class', 'ess-limbo') ?></label></dt>
            <dd><input id="class" type="text" ng-model="options.itemclass" placeholder="Item class"></dd>

            <dt><label><?php _e('Image sizes', 'ess-limbo') ?></label></dt>
            <dd>
                <label for="fullimage"><?php _e('Full', 'ess-limbo') ?></label>
                <input id="fullimage" type="number" ng-model="options.thumbs.full" min="100" placeholder="250">
                <label for="mediumimage"><?php _e('Medium', 'ess-limbo') ?></label>
                <input id="mediumimage" type="number" ng-model="options.thumbs.medium" min="100" placeholder="170">
                <label for="smallimage"><?php _e('Small', 'ess-limbo') ?></label>
                <input id="smallimage" type="number" ng-model="options.thumbs.small" min="100" placeholder="100">
            </dd>

            <dt><label for="fheight"><?php _e('Features height', 'ess-limbo') ?></label></dt>
            <dd>
                <input id="fheight" type="number" ng-model="options.features.height" min="100" placeholder="250">
            </dd>

            <dt><label for="ltitle"><?php _e('List title', 'ess-limbo') ?></label></dt>
            <dd>
                <input id="ltitle" type="text" ng-model="options.list.title" placeholder="News">
            </dd>

        </dl>

         <!-- Form submission fields -->
        <form action="#" method="post">
            <input type="hidden" name="action" value="save">
            <input type="text" id="ess_limbo_options" ng-model="options" class="hidden">
            <input type="text" id="ess_limbo_options" name="ess_limbo_options" ng-model="saved" ng-value="options | json:0"  class="hidden">
            <button class="ess-save-button" type="submit"><?php _e('Save options', 'ess-limbo') ?></button>
        </form>
    </fieldset>
</section>

<!-- Options from the database -->
<script>
    var loptions, savedOptions;
    loptions = '<?php echo get_option('ess_limbo_options'); ?>';
    window.options = '';
    if (loptions !== '') {
        savedOptions = JSON.parse(loptions);
        window.options = JSON.stringify(savedOptions);
        console.log(window.options);
    }
</script>
