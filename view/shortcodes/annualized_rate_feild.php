<?php

$rate = get_post_meta( get_the_ID(), "ETF-Pre-annualized-distribution-rate-data", true );

?>

<?php  if (strtolower(the_title('', '', false)) === "cefz") { ?>
    <div class="elementor-container elementor-column-gap-default">
        <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-0c7b358 col-min-80" data-id="0c7b358" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-530df8a pnospace elementor-widget elementor-widget-text-editor" data-id="530df8a" data-element_type="widget" data-widget_type="text-editor.default">
                    <div class="elementor-widget-container">
                        <p>Annualized Distribution Rate</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-6e276e6 col-min-80" data-id="6e276e6" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-89d4596 pnospace elementor-widget elementor-widget-text-editor" data-id="89d4596" data-element_type="widget" data-widget_type="text-editor.default">
                    <div class="elementor-widget-container"> <?= $rate ?> </div>
                </div>
            </div>
        </div>
    </div>
<?php  } ?>

