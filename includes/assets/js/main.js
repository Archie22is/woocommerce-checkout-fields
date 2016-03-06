var availableClasses = [
               "form-row-wide",
               "form-row-first",
               "form-row-last",
            ];

jQuery(document).ready(function() {
    jQuery("#accordion").accordion({ header: "h3", collapsible: true, active: false });

    jQuery('#addNewBillingField').click(function(){
    	createNewField('billing_')
    });

    jQuery('#addNewShippingField').click(function(){
    	createNewField('shipping_')
    });

    jQuery( ".datepicker input" ).datepicker({
    	dateFormat: 'dd/mm/yy',
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    nextText: 'Próximo',
	    prevText: 'Anterior'
    });

    jQuery('.classes').autocomplete({source: availableClasses});
})

function createNewField(type){
	wcfElement = '<h3>Novo Campo</h3>';
	wcfElement += '<div style="background:transparent !important">'
	wcfElement += '<table class="form-table"><tbody>';
	wcfElement += '<tr class="form-field"> <th scope="row"><label for="">Key:</th> <td id="input_key">-</td></tr>';
	wcfElement += '<tr class="form-field"> <th scope="row"><label for="">Tipo:</th> <td><select name="'+type+'type[]" id=""><option>text</option><option>textarea</option><option>password</option><option>select</option><option>radio</option><option>datepicker</option></select></td></tr>';
	wcfElement += '<tr class="form-field"><th scope="row"><label for="">Label:</th><td><input name="'+type+'label[]" type="text" id=""></td></tr>';
	wcfElement += '<tr class="form-field"><th scope="row"><label for="">Classe:</th><td><input type="text" name="'+type+'class[]" class="classes"></td></tr>';
	wcfElement += '<tr class="form-field"><th scope="row"><label for="">Placeholder:</th><td><input name="'+type+'placeholder[]" type="text" id=""></td></tr>';
	wcfElement += '<tr class="form-field"><th scope="row"><label for="">Clear Fix:</th><td><select name="'+type+'clear[]"><option value="1">Sim</option><option value="0">Não</option></select></td></tr>';
	wcfElement += '<tr class="form-field"><th scope="row"><label for="">Obrigatório:</th><td><select name="'+type+'required[]"><option value="1">Sim</option><option value="0">Não</option></select></td></tr>';
	wcfElement += '<tr class="form-field"><th scope="row"><label for="">Options: <small>Utilize sempre valor do campo = label a ser exibido com ";" no final.</small></th><td><textarea  name="'+type+'options[]" id="">0=Selecionar;</textarea></td></tr>';
	wcfElement += '<tr class="form-field"><th scope="row"><label for="">Visível ao Administrador:</th><td><select name="'+type+'admin[]"><option value="1">Sim</option><option value="0">Não</option></select></td></tr>';
	wcfElement += '<tr class="form-field"><th scope="row"><label for="">Enviar no E-mail:</th><td><select name="'+type+'email[]"><option value="1">Sim</option><option value="0">Não</option></select></td></tr>';
	wcfElement += '</tbody></table>';
	wcfElement += '</div>';

	jQuery('#elements').append(wcfElement);
	jQuery('#accordion').accordion('refresh');
    jQuery('.classes').autocomplete({source: availableClasses});
}

function removeField(field){
	jQuery('#'+field).remove();
}