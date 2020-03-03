<?php if(!empty($pluginsAutoLoad)): ?>
    <?php foreach ($pluginsAutoLoad as $value => $item): ?>
        <?php if(!empty($item["js"])): ?>

            <?php foreach ($item["js"] as $jP): ?>
                <script src='<?= BASE_URL; ?>assets/plugins/<?= $value ?>/<?= $jP ?>.js'></script>
            <?php endforeach; ?>

        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>


    <!-- AutoLoad de JS -->
<?php if(!empty($js)): ?>

    <!-- Plugins -->
    <?php if(!empty($js["plugins"])): ?>

        <?php foreach ($js["plugins"] as $j): ?>
            <script src='<?= BASE_URL; ?>assets/<?= $j ?>.js'></script>
        <?php endforeach; ?>

    <?php endif; ?>



    <!-- Módulos -->
    <?php if(!empty($js["modulos"])): ?>

        <?php foreach ($js["modulos"]as $j): ?>
            <script type='module' src='<?= BASE_URL; ?>assets/app/method/<?= $j ?>.js'></script>
        <?php endforeach; ?>

    <?php endif; ?>


    <!-- Páginas -->
    <?php if(!empty($js["pages"])): ?>

        <?php foreach ($js["pages"]as $j): ?>
            <script src='<?= BASE_URL; ?>assets/app/page/<?= $j ?>.js'></script>
        <?php endforeach; ?>

    <?php endif; ?>

<?php endif; ?>