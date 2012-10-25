
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
				
					
					function loadPublicacoes(url, editable, pagina) {
						if (!pagina) pagina = 1;
						if (typeof editable == 'undefined') editable = false;
						// tabela de publicações 
						jQuery('#trabs_placeholder').load(url+'catalogo-table-tpl.php', {pagina : pagina, editable : editable}, function(response, status, xhr) {
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
								jQuery('.pagination').unbind('click').bind('click', function() {
									var option = jQuery(this).attr('id');
									switch (option) {
										case 'first_pag':
											pagina = 1;
											break;
										case 'previous_pag':
											pagina -= 1;
											if(pagina < 1) pagina = 1;
											break;	
										case 'next_pag':
											pagina += 1;
											break;
										case 'last_pag':
											pagina = jQuery('#total_pag').val();
											break;
										default:
											return;
											break;
									}

									loadPublicacoes(url,editable,pagina);
								});

								// trocando de página pelo valor digitado 
								jQuery('#custom_pag').keypress(function(e) {
								    if(e.which == 13) {
								        var input = jQuery(this).val();
								        var gtOneREG = /^[1-9][0-9]*$/;
								        if(gtOneREG.test(input)) pagina = input;
								        loadPublicacoes(url, editable, pagina);
								    }
								});
								
							  	// identificando opção clicada 
							  	jQuery('#table_publicacoes input.edit-table').unbind('click').bind('click', function() {
									var option = jQuery(this).attr('id');
									var data = null;
									var dlg = jQuery('#catalogo_dialog');
									
									switch (option) {
										case 'del_trab':
											if(!itemID) {
												alert('Selecione uma publicação!');
											} else {
												data = {id : itemID, option : option};
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
															jQuery.post(url+'catalogo-actions.php', data, function(data) {
														    	if(data=="error") {
															    	alert("Ocorreu um erro ao tentar realizar a ação!");
														    	} else {
														    		option = undefined;
														    		loadPublicacoes(url, editable, pagina);
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
												data = {itemID : itemID, option : option};
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
															jQuery.post(url+'catalogo-actions.php', jQuery('#trabForm').serialize(), function(data) {
														    	if(data=="error") {
															    	alert("Ocorreu um erro ao tentar realizar a ação!");
														    	} else {
														    		loadPublicacoes(url, editable, pagina);
														    	}
															});
				
															jQuery(this).dialog('close');
														}
													}
												]);
												dlg.load(url+'catalogo-form-tpl.php', data).dialog('open');
											}
											break;
											
										case 'new_trab':
											data = {option : option};
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
															jQuery.post(url+'catalogo-actions.php', jQuery('#trabForm').serialize(), function(data) {
														    	if(data=="error") {
															    	alert("Ocorreu um erro ao tentar realizar a ação!");
														    	} else {
														    		loadPublicacoes(url, editable, pagina);
														    	}
															});
				
															jQuery(this).dialog('close');
														}
													}
												]);
											dlg.load(url+'catalogo-form-tpl.php', data).dialog('open');
											break;
									}
								});
							
							}
						});
					
					}

					// tabela de palavras-chave 
					function loadChaves(url) {
						jQuery("#chaves_placeholder").load(url+'catalogo-chaves-table-tpl.php', function(response, status, xhr) {
							if (status == "error") {
						    	var msg = 'Desculpe, mas ocorreu um erro. Reporte-o ao administrador: ';
						    	jQuery(this).html(msg + xhr.status + ' ' + xhr.statusText);
							} else {
								// selecionando palavra-chave 
								var chaveID = null;
								jQuery('tr.catalogo-chave').unbind('click').bind('click', function() {
									jQuery('tr.catalogo-chave').removeClass('selected');
									jQuery(this).addClass('selected');
									chaveID = jQuery(this).attr('id').split('-');
									chaveID = chaveID[1];
								});
								// identificando opção clicada 
								jQuery('#table_chaves input.edit-table').unbind('click').bind('click', function(event) {
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
												jQuery.post(url+'catalogo-actions.php', data, function(data) {
											    	if(data=="error") {
												    	alert("Ocorreu um erro ao tentar realizar a ação!");
											    	} else {
											    		loadChaves(url);
											    	}
												});
											}
											break;
									}
		
									jQuery('#send_chave').unbind('click').bind('click', function() {
										// @todo validar newPalavra 
										var newPalavra = jQuery('#new_palavra').val();
										data = {chaveID : chaveID, option : option, palavra : newPalavra};
										jQuery.post(url+'catalogo-actions.php', data, function(data) {
									    	if(data=="error") {
										    	alert("Ocorreu um erro ao tentar realizar a ação!");
									    	} else {
									    		loadChaves(url);
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