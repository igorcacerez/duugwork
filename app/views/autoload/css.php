<?php if(!empty($pluginsAutoLoad)): ?>
    <?php foreach ($pluginsAutoLoad as $value => $item): ?>
        <?php if(!empty($item["css"])): ?>

            <?php foreach ($item["css"] as $cssP): ?>
                <link rel="stylesheet" href="<?= BASE_URL; ?>assets/plugins/<?= $value ?>/<?= $cssP ?>.css" />
            <?php endforeach; ?>

        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>


<!-- AutoLoad de CSS -->
<?php if(!empty($css)): ?>

    <?php foreach ($css as $c): ?>
        <link rel="stylesheet" href="<?= BASE_URL; ?>assets/<?= $c ?>.css" />
    <?php endforeach; ?>

<?php endif; ?>


<link rel="stylesheet" href="<?= BASE_URL; ?>assets/custom/css/estilo.css" />
<link rel="stylesheet" href="<?= BASE_URL; ?>assets/custom/css/responsivo.css" />