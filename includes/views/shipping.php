<form action="" method="post">
<div id="accordion">
<div id="elements">
    <?php
    $message = '<div class="alert alert-warning"> <strong>Importante!</strong> Este campo pertence ao core da aplicação, ou a um plugin de terceiro e por este motivo não pode ser removido </div>';
    if(!$getShippingFields):
        # Starter
        foreach($shippingfields as $k => $field ):
    ?>
        <div id="field_<?php print $k; ?>">
            <h3> <a href="javascript:void(0)"><?php print $field['label'] ?></a> </h3>
            <div style="background:transparent !important">
            <?php
                print($message);
                $disabled_fields = 'disabled="disabled"';
                $disabled = 1;
                include 'form_fields.php';
            ?>
            </div>
        </div>
    <?php
        endforeach;
    else:
        # After update fields or add new fields
        foreach($getShippingFields['fields'] as $field ): 
    ?>
        <div id="field_<?php print $field['shipping_key']; ?>">
            <h3> <a href="javascript:void(0)"><?php print $field['shipping_label']; ?></a> </h3>
            <div style="background:transparent !important">
            <?php 
                if($field['shipping_native'] > 0){
                    print($message);
                    $disabled_fields = 'disabled="disabled"';
                    $disabled = 1;
                } else {
                    $disabled_fields = '';
                    $disabled = 0;
                }
                include 'form_edited_fields.php';
            ?>
            </div>
        </div>
    <?php
        endforeach;
    endif;
    ?>
	
</div>
    <?php wp_nonce_field( 'request_wcf_shipping_action', 'request_wcf_shipping_nonce_field' ); ?>
    <p class="submit">
    	<button type="button"
    			id="addNewShippingField"
    			class="button button-primary">
    			Novo Campo
    	</button>
    	Ou
    	<input type="submit" id="createusersub" class="button button-primary" value="Salvar">
    </p>
</div>
</form>

<?php if($getShippingFields): ?>
    <form action="" method="post">
        <?php wp_nonce_field( 'request_wcf_shipping_reset_action', 'request_wcf_shipping_reset_nonce_field' ); ?>
        <input type="submit" class="button button-default" value="Resetar">
    </form>
<?php endif; ?>