
jQuery.fn.visible = function() {
    return this.css('visibility', 'visible');
};

jQuery.fn.invisible = function() {
    return this.css('visibility', 'hidden');
};

jQuery.fn.visibilityToggle = function() {
    return this.css('visibility', function(i, visibility) {
        return (visibility == 'visible') ? 'hidden' : 'visible';
    });
};


				
					
function loadPublicacoes(options) {
	var ajaxIndicator = '<div id="loading" style="height: 100%; background: url(' + options['url'] + 'img/ajax-loader.gif) no-repeat scroll center center;"></div>';
	// opções padrão
	var defaultArgs = {
			url			:	'http://www.pudim.com.br',
			editable	:	false,
			filter		:	false,
			page		:	1
	};
	// atribuindo opções padrão para as opções omitidas na chamada da função
	for(var key in defaultArgs) {
		if(typeof options[key] == "undefined") options[key] = defaultArgs[key];
	}
	
	// tabela de publicações 
	jQuery('#trabs_placeholder').html(ajaxIndicator).load(options['url']+'catalogo-table-tpl.php', {page : options['page'], editable : options['editable'], filter : options['filter']}, function(response, status, xhr) {
		if (status == "error") {
	    	var msg = 'Desculpe, mas ocorreu um erro. Reporte-o ao administrador: ';
	    	jQuery(this).html(msg + xhr.status + ' ' + xhr.statusText);
		} else {

			// selecionando publicação 
			var itemID = null;
			jQuery('tr.catalogo').click(function() {
				var item = jQuery(this);
				if (item.hasClass('selected')) {
					jQuery('tr.catalogo').removeClass('selected');
					itemID = null;
				} else {
					jQuery('tr.catalogo').removeClass('selected');
					item.addClass('selected');
					itemID = item.attr('id').split('-');
					itemID = itemID[1];
				}
			});

			// trocando de página pelos botões 
			jQuery('.paginate').unbind('click').bind('click', function() {
				var option = jQuery(this).attr('id');
				switch (option) {
					case 'first_pag':
						options['page'] = 1;
						break;
					case 'previous_pag':
						options['page'] -= 1;
						if(options['page'] < 1) options['page'] = 1;
						break;	
					case 'next_pag':
						options['page'] += 1;
						break;
					case 'last_pag':
						options['page'] = jQuery('#total_pag').val();
						break;
					default:
						return;
						break;
				}
				loadPublicacoes(options);
			});

			// trocando de página pelo valor digitado 
			jQuery('#custom_pag').keypress(function(e) {
			    if(e.which == 13) {
			        var input = jQuery(this).val();
			        var totalPag = jQuery('#total_pag').val();
			        var gtOneREG = /^[1-9][0-9]*$/;
			        if(gtOneREG.test(input)) {
			            if(input > totalPag)
			                 options['page'] = totalPag;
			            else
			                 options['page'] = input;
			        }
			        loadPublicacoes(options);
			    }
			});
			
		  	// identificando opção clicada 
		  	jQuery('#table_publicacoes button.edit-table').unbind('click').bind('click', function() {
				var option = jQuery(this).attr('id');
				var data = null;
				var dlg = jQuery('#catalogo_dialog');
				
				switch (option) {
					case 'down_trab':
						if(!itemID){
							alert('Selecione uma publicação!');
						} else {
							var link = jQuery('tr.catalogo.selected').find('input.download-link').val();
							if (typeof link == 'undefined') alert('Não há download para essa publicação!');
							else window.location.href = link;
							data = {itemID : itemID, option : option};
							jQuery.post(options['url']+'catalogo-actions.php', data, function(data) {
						    	if(data=="error") {
							    	alert("Ocorreu um erro ao tentar realizar a ação!");
						    	} else {
						    		option = undefined;
						    	}
							});
						}
						break;
						
					case 'del_trab':
						if(!itemID) {
							alert('Selecione uma publicação!');
						} else {
							var fileName = jQuery('#item-'+itemID).find('.file-name').val();
							data = {id : itemID, option : option, fileName : fileName};
							dlg.dialog("option", "title", "Confirmar ação");
							dlg.dialog("option", "buttons", [
								{
									'text'	: 'Cancelar',
									'class' : 'button',
									'click' : function() {
										jQuery(this).dialog('close');
									}
								},
								{
									'text'	: 'Ok',
									'class' : 'button-primary',
									'click' : function() {
										jQuery.post(options['url']+'catalogo-actions.php', data, function(data) {
									    	if(data=="delfile") {
										    	alert("Ocorreu um erro ao tentar deletar o arquivo!");
										    } else if(data=="db") {
										        alert("Ocorreu um erro ao tentar deletar a publicação!");
									    	} else {
									    		option = undefined;
									    		loadPublicacoes(options);
									    	}
										});

										jQuery(this).dialog('close');
									}
								}
							]);
							
							dlg.html('Tem certeza que deseja deletar a publicação? Essa ação não pode ser revertida.').dialog('open');
						}
						break;
						
					case 'edit_trab':
						if(!itemID) {
							alert('Selecione uma publicação!');
						} else {
							dlg.dialog("option", "title", "Alterar publicação");
							dlg.dialog("option", "buttons", [
								{
									'text' : 'Cancelar',
									'class' : 'button',
									'click' : function() {
										jQuery(this).dialog('close');
									}
								},
								{
									'text' : 'Salvar',
									'class' : 'button-primary',
									'click' : function() {
										// publicar conteúdo e recarregar tabela de catálogos 
										jQuery('#selected_chaves option').attr('selected', 'selected');
										var files = '';
										jQuery('.uploaded-files').each(function(i){
							    			files += jQuery(this).val() + ',';
							    		});
										files = files.slice(0, - 1);
										jQuery('#arquivos').val(files);
										jQuery.post(options['url']+'catalogo-actions.php', jQuery('#trabForm').serialize(), function(data) {
									    	if(data=="error") {
										    	alert("Ocorreu um erro ao tentar realizar a ação!");
									    	} else {
									    		loadPublicacoes(options);
									    	}
										});

										jQuery(this).dialog('close');
									}
								}
							]);
							
							data = {itemID : itemID, option : option};
							dlg.html(ajaxIndicator).load(options['url']+'catalogo-form-tpl.php', data, createUploader).dialog('open');
						}
						break;
						
					case 'new_trab':
						dlg.dialog("option", "title", "Nova publicação");
						dlg.dialog("option", "buttons", [
							{
								'text' : 'Cancelar',
								'class' : 'button',
								'click' : function() {
									jQuery(this).dialog('close');
								}
							},
							{
								'text' : 'Salvar',
								'class' : 'button-primary',
								'click' : function() {
									// publicar conteúdo e recarregar tabela de catálogos 
									jQuery('#selected_chaves option').attr('selected', 'selected');
									var files = '';
									jQuery('.uploaded-files').each(function(i){
						    			files += jQuery(this).val() + ',';
						    		});
									files = files.slice(0, - 1);
									jQuery('#arquivos').val(files);
									jQuery.post(options['url']+'catalogo-actions.php', jQuery('#trabForm').serialize(), function(data) {
								    	if(data=="error") {
									    	alert("Ocorreu um erro ao tentar realizar a ação!");
								    	} else {
								    		loadPublicacoes(options);
								    	}
									});

									jQuery(this).dialog('close');
								}
							}
						]);
						
						data = {option : option};
						dlg.html(ajaxIndicator).load(options['url']+'catalogo-form-tpl.php', data, createUploader).dialog('open');
						break;
					case 'filter_trab':
						dlg.dialog("option", "title", "Filtrar publicações");
						dlg.dialog("option", "buttons", [
							{
								'text' : 'Cancelar',
								'class' : 'button',
								'click' : function() {
									jQuery(this).dialog('close');
								}
							},
							{
								'text' : 'Pesquisar',
								'class' : 'button-primary',
								'click' : function() {
									// publicar conteúdo e recarregar tabela de catálogos 
									jQuery('#selected_chaves option').attr('selected', 'selected');
									
									var opts = '';
						    		jQuery('#selected_chaves option').each(function(i){
						    			opts += jQuery(this).val() + ',';
						    		});
						    		options['filter'] = {
						    				chaves	:	opts.slice(0, - 1),
						    				autor	:	jQuery('#autor').val()
						    		};

						    		loadPublicacoes(options);

									jQuery(this).dialog('close');
								}
							}
						]);						
						
						data = {option : option, filters : options['filter']};
						dlg.html(ajaxIndicator).load(options['url']+'catalogo-form-tpl.php', data).dialog('open');
						break;
				}
			});
		
		}
	});

}

// tabela de palavras-chave 
function loadChaves(options) {
	var ajaxIndicator = '<div id="loading" style="height: 100%; background: url(' + options['url'] + 'img/ajax-loader.gif) no-repeat scroll center center;"></div>';
	
	var defaultArgs = {
        url         :   'http://www.pudim.com.br',
        page        :   1
    };
    // atribuindo opções padrão para as opções omitidas na chamada da função
    for(var key in defaultArgs) {
        if(typeof options[key] == "undefined") options[key] = defaultArgs[key];
    }
	
	jQuery("#chaves_placeholder").html(ajaxIndicator).load(options['url']+'catalogo-chaves-table-tpl.php', {page : options['page']}, function(response, status, xhr) {
		if (status == "error") {
	    	var msg = 'Desculpe, mas ocorreu um erro. Reporte-o ao administrador: ';
	    	jQuery(this).html(msg + xhr.status + ' ' + xhr.statusText);
		} else {
		    
			// selecionando palavra-chave 
			var chaveID = null;
			jQuery('tr.catalogo-chave').unbind('click').bind('click', function() {
			    var chave = jQuery(this);
			    if (chave.hasClass('selected')) {
			        jQuery('tr.catalogo-chave').removeClass('selected');
			        chaveID = null;
			    } else {
			        jQuery('tr.catalogo-chave').removeClass('selected');
			        chave.addClass('selected');
			        chaveID = chave.attr('id').split('-');
			        chaveID = chaveID[1];
			    }
			});
			
			// trocando de página pelos botões 
            jQuery('.paginate_chave').unbind('click').bind('click', function() {
                var option = jQuery(this).attr('id');
                switch (option) {
                    case 'first_pag_chave':
                        options['page'] = 1;
                        break;
                    case 'previous_pag_chave':
                        options['page'] -= 1;
                        if(options['page'] < 1) options['page'] = 1;
                        break;  
                    case 'next_pag_chave':
                        options['page'] += 1;
                        break;
                    case 'last_pag_chave':
                        options['page'] = jQuery('#total_pag_chave').val();
                        break;
                    default:
                        return;
                        break;
                }
                loadChaves(options);
            });

            // trocando de página pelo valor digitado 
            jQuery('#custom_pag_chave').keypress(function(e) {
                if(e.which == 13) {
                    var input = jQuery(this).val();
                    var totalPag = jQuery('#total_pag_chave').val();
                    var gtOneREG = /^[1-9][0-9]*$/;
                    if(gtOneREG.test(input)) {
                        if(input > totalPag)
                             options['page'] = totalPag;
                        else
                             options['page'] = input;
                    }
                    loadChaves(options);
                }
            });
            
			// identificando opção clicada 
			jQuery('#table_chaves button.edit-table').unbind('click').bind('click', function(event) {
				var option	= jQuery(this).attr('id');
				var data	= null;
				var linha	= jQuery('tr.selected');
				var input	= jQuery('tr#chaveForm');
				
				event.preventDefault;

				switch (option) {
					case 'new_chave':
						jQuery('tr').show().filter(input).appendTo('#table_chaves').children('td.td-id').html('');
						jQuery('#new_palavra').val('');
						break;
					case 'edit_chave':
						if(!chaveID){
							alert('Selecione uma publicação!');
						} else {
							// mostra todas as linhas menos a linha a ser editada 
							jQuery('tr').show().filter(linha).hide();
							// coloca a linha input antes da linha a ser editada 
							linha.before(input).hide();
							// mostra o ID da chave na céluda ID da linha de input 
							input.children('td.td-id').html(chaveID);
							// pré-popula o input da palavra a editar 
							jQuery('#new_palavra').val(linha.children('.td-palavra').text());
						}
						break;
					case 'del_chave':
						if(!chaveID){
							alert('Selecione uma publicação!');
						} else {
							data = {chaveID : chaveID, option : option};
							jQuery.post(options['url']+'catalogo-actions.php', data, function(data) {
						    	if(data=="error") {
							    	alert("Ocorreu um erro ao tentar realizar a ação!");
						    	} else {
						    		loadChaves(options);
						    	}
							});
						}
						break;
				}

				jQuery('#send_chave').unbind('click').bind('click', function() {
					// @todo validar newPalavra 
					var newPalavra = jQuery('#new_palavra').val();
					data = {chaveID : chaveID, option : option, palavra : newPalavra};
					jQuery.post(options['url']+'catalogo-actions.php', data, function(data) {
				    	if(data=="error") {
					    	alert("Ocorreu um erro ao tentar realizar a ação!");
				    	} else {
				    		loadChaves(options);
				    	}
					});
				});
				
				jQuery('#cancel_chave').click(function() {
					// mostra todas as linhas menos a linha a ser editada 
					jQuery('tr').show().filter(input).hide();
					chaveID = null;
				});

			});

		}
		
	});

}

