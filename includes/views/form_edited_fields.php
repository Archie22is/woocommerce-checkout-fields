 <table class="form-table">
	<tbody>

	<tr class="form-field">
		<th scope="row"><label for="">Key:</th>
		<td>
		<?php
			print $field[$prefix.'key'];
			$field_key = 'field_'.$field[$prefix.'key'];
			$options = '';
			if($disabled == 1){
				if(isset($field[$prefix.'options'])){
					foreach($field[$prefix.'options'] as $sField => $v){
						$options .= $sField.'='.$v. ';';
					}
				}else{$options = '0=Selecionar;';}
				printf('<input type="hidden" name="%s" value="%s">', $prefix.'key[]', 	  $field[$prefix.'key']);
				printf('<input type="hidden" name="%s" value="%s">', $prefix.'type[]', 	  $field[$prefix.'type']);
				printf('<input type="hidden" name="%s" value="%s">', $prefix.'clear[]',	  $field[$prefix.'clear']);
				printf('<input type="hidden" name="%s" value="%s">', $prefix.'required[]',$field[$prefix.'required']);
				printf('<input type="hidden" name="%s" value="%s">', $prefix.'options[]',   $options);
				printf('<input type="hidden" name="%s" value="%s">', $prefix.'admin[]',   1);
			}
			else{
				printf('<input type="hidden" name="%s" value="%s">', $prefix.'key[]', 	  $field[$prefix.'key']);
			}
			printf('<input type="hidden" name="%s" value="%s">', $prefix.'native[]',    $field[$prefix.'native']);
		?>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Tipo:</th>
		<td>
			<select name="<?php print $prefix; ?>type[]" <?php print $disabled_fields; ?>>
				<option <?php print ($field[$prefix.'type'] == 'text' || $field['type'] == '') ?'selected="selected"' : ''; ?>>text</option>
				<option <?php print ($field[$prefix.'type'] == 'textarea') ?'selected="selected"' : ''; ?>>textarea</option>
				<option <?php print ($field[$prefix.'type'] == 'password') ?'selected="selected"' : ''; ?>>password</option>
				<option <?php print ($field[$prefix.'type'] == 'select') ?'selected="selected"' : ''; ?>>select</option>
				<option <?php print ($field[$prefix.'type'] == 'radio') ?'selected="selected"' : ''; ?>>radio</option>
				<option <?php print ($field[$prefix.'type'] == 'datepicker') ?'selected="selected"' : ''; ?>>datepicker</option>
			</select>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Label:</th>
		<td><input name="<?php print $prefix; ?>label[]" type="text" id="" value="<?php print $field[$prefix.'label']; ?>"></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Classe:</th>
		<td>
			<input name="<?php print $prefix; ?>class[]" type="text" id="" class="classes" value="<?php print $field[$prefix.'class']; ?>"></td>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Placeholder:</th>
		<td><input name="<?php print $prefix; ?>placeholder[]" type="text" id="" value="<?php print $field[$prefix.'placeholder']; ?>"></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Clear Fix:</th>
		<td>
			<select name="<?php print $prefix; ?>clear[]" <?php print $disabled_fields; ?>>
				<option value="0" <?php print ($field[$prefix.'clear'] == 0) ?'selected="selected"' : ''; ?>>Não</option>
				<option value="1" <?php print ($field[$prefix.'clear'] == 1) ?'selected="selected"' : ''; ?>>Sim</option>
			</select>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Obrigatório:</th>
		<td>
			<select name="<?php print $prefix; ?>required[]" <?php print $disabled_fields; ?>>
				<option value="1" <?php print ($field[$prefix.'required'] == 1) ?'selected="selected"' : ''; ?>>Sim</option>
				<option value="0" <?php print ($field[$prefix.'required'] == 0) ?'selected="selected"' : ''; ?>>Não</option>
			</select>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Options: <small>Utilize sempre valor do campo = label a ser exibido com ";" no final.</small></th>
		<td><textarea name="<?php print $prefix; ?>options[]" id="" <?php print $disabled_fields; ?>><?php 

		if(isset($field[$prefix.'options'])){
			foreach($field[$prefix.'options'] as $k => $v){
				echo $k,'=',$v, ';';
			}
		}
		else {
			echo '0=Selecionar;';
		}
		?></textarea></td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Visível ao Administrador:</th>
		<td>
			<select name="<?php print $prefix; ?>admin[]"  <?php print $disabled_fields; ?>>
				<option value="1" <?php print ($field[$prefix.'admin'] == 1) ?'selected="selected"' : ''; ?>>Sim</option>
				<option value="0" <?php print ($field[$prefix.'admin'] == 0) ?'selected="selected"' : ''; ?>>Não</option>
			</select>			
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="">Visível no E-mail:</th>
		<td>
			<select name="<?php print $prefix; ?>email[]" >
				<option value="1" <?php print ($field[$prefix.'email'] == 1) ?'selected="selected"' : ''; ?>>Sim</option>
				<option value="0" <?php print ($field[$prefix.'email'] == 0) ?'selected="selected"' : ''; ?>>Não</option>
			</select>			
		</td>
	</tr>

	<?php if($field[$prefix.'native'] == 0): ?>
	<tr class="form-field">
		<th scope="row">
			<p class="submit">
	    		<input type="button" 
	    			class="button button-primary"
	    			onclick="removeField('<?php print $field_key; ?>')"
	    			value="Remover Campo">
	    	</p>
		</th>
	</tr>
	<?php endif; ?>
	</tbody>
</table>