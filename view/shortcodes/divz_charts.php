<?php 
    $divz_values = get_option('divz-chart-values');
    $divz_values_opt = json_decode($divz_values, true);
?>
<script>
    var etfs_divz_charts_values = {
        'no_stocks': {
            'divz': <?php echo $divz_values_opt['no_stocks']['divz']; ?>,
            'sp': <?php echo $divz_values_opt['no_stocks']['sp']; ?>
        },
        'ps': {
            'divz': <?php echo $divz_values_opt['ps']['divz']; ?>,
            'sp': <?php echo $divz_values_opt['ps']['sp']; ?>
        },
        'pe': {
            'divz': <?php echo $divz_values_opt['pe']['divz']; ?>,
            'sp': <?php echo $divz_values_opt['pe']['sp']; ?>
        },
        'pb': {
            'divz': <?php echo $divz_values_opt['pb']['divz']; ?>,
            'sp': <?php echo $divz_values_opt['pb']['sp']; ?>
        },
        'avg': {
            'divz': <?php echo $divz_values_opt['avg']['divz']; ?>,
            'sp': <?php echo $divz_values_opt['avg']['sp']; ?>
        }
    }
</script>