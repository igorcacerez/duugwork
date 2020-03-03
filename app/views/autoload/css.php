<?php if(!empty($pluginsAutoLoad)): ?>
    <?php foreach ($pluginsAutoLoad as $value => $item): ?>
        <?php if(!empty($item["css"])): ?>

            <?php foreach ($item["css"] as $cssP): ?>
                <script rel="stylesheet" src='<?= BASE_URL; ?>assets/plugins/<?= $value ?>/<?= $cssP ?>.js'></script>
            <?php endforeach; ?>

        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>


    <!-- AutoLoad de CSS -->
<?php if(!empty($css)): ?>

    <?php foreach ($css as $c): ?>
        <link rel="stylesheet" src='<?= BASE_URL; ?>assets/<?= $c ?>.css' />
    <?php endforeach; ?>

<?php endif; ?>