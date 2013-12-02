/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(window).resize(function() {
    if ($(window).width() > 480){
        var height = $(window).height() - 100;
        $('.techsec-menu-lateral').css('height',height);
    }
});

$(window).load(function () {
    if($(window).width() > 480){
        var height = $(window).height() - 100;
        $('.techsec-menu-lateral').css('height',height);
    }
  
  if($.browser.name == 'safari')
  {
        $('.techsec-date').datepicker({
          dateFormat: 'dd/mm/yy',
          dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
          dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
          dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
          monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
          monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
          nextText: 'Próximo',
          prevText: 'Anterior'
      });
  }
});

function buscaOS(){
    
    $('#search-txt').val(function(index,value){
        if (value.length > 0)
           value = btoa(value);
        else
           value = 0;
       
        $.getJSON('/os/filter-os/' + value, function(data){
            var items = "";
            
            $.each(data, function(key, val){
                var ls_codOS = val.CodOS;
                var aux = val.DataAbertura.split(" ")[0].split("-");
                var ls_dataAbertura = aux[2] + "/" + aux[1] + "/" + aux[0];
                var ls_motivo = val.Motivo;
                var ls_descricao = val.Descricao;
                
                
                
                items += "<div class='techsec-menu-item' onclick='showOS(" + ls_codOS + ")'>";
                items += "<div class='num-os'>" + ls_codOS + "</div>";
                items += "<div class='detail-os'>";
                items += "<div class='data-os'>" + ls_dataAbertura + "</div>";
                items += "<div class='motivo-os'>" + ls_motivo + "</div>";
                items += "<div class='descricao-os'>" + ls_descricao.toLowerCase() + "</div>";
                items += "</div></div>";
            });
            
            $('.techsec-menu-lateral-pesquisa').html(items);
        });       
    });
}

function listOS(){
    if($(window).width() <= 480){
        $('.techsec-menu-lateral').fadeIn();
        $('.techsec-menu-lateral-pesquisa').fadeIn();
        $('.techsec-box-search').fadeIn();
        $('#btn-listarOS').hide();
    }
}

function listar(){
    if($(window).width() <= 480){
        $('.techsec-menu-lateral').fadeIn();
        $('#btn-listar').hide();
        $('#btn-voltar').show();
    }
}

function showOS(codOS, codTecnico){
    if($(window).width() <= 480){
        $('.techsec-menu-lateral').fadeOut();
        $('.techsec-menu-lateral-pesquisa').fadeOut();
        $('.techsec-box-search').fadeOut();
        $('#btn-listarOS').show();
    }
    $.getJSON('/os/view-os/' + codOS, function(data) {
        var dta = data[0].DataAbertura.split(" ");
        dta = dta[0].split("-");
        var os_dataAbertura = dta[2] + "/" + dta[1] + "/" + dta[0];
        
        var os_dataFechamento = null;
        
        if(data[0].DataFechamento){
            var dtf = data[0].DataFechamento.split(" ");
            dtf = dtf[0].split("-");
            os_dataFechamento = dtf[2] + "/" + dtf[1] + "/" + dtf[0];
        }
        
        var os_codOS = data[0].CodOS;
        var os_motivo = data[0].Motivo;
        var os_cliente = data[0].raz_cli; 
        var os_tipo = data[0].Tipo;
        var os_status = data[0].Status;
        var os_prioridade = data[0].Prioridade;
        var os_endereco = data[0].Endereco;
        var os_descricao = data[0].Descricao;
        
        
        $('.view-os-data').html(os_dataAbertura);
        if(codTecnico){
            $('.view-os-title').html("[OS - "+os_codOS+"] - "+os_motivo);
        }else{
            $('.view-os-title').html(os_motivo);
        }
        $('#abertura_os').html(os_dataAbertura);
        if(data[0].DataFechamento && codTecnico){
            var inner = "<strong>Data de Fechamento: </strong><span id='fechamento_os'>"+os_dataFechamento+"</span>";
            $('#datafechamento_os').html(inner);
        }else{
            $('#datafechamento_os').html('');
        }   
        $('#cliente_os').html(os_cliente);
        $('#tipo_os').html(os_tipo);
        $('#status_os').html(os_status);
        $('#prioridade_os').html(os_prioridade);
        $('#endereco_os').html(os_endereco);
        $('#descricao_os').html(os_descricao);
        $('#btn-tratarOS').attr('onclick', 'doAtend('+os_codOS+')');
    });
    
    $.getJSON('/os/view-os-atend/' + codOS, function(data) {
        var atend = "<div class='view-atendimentos-header'><div class='view-title'>Atendimentos</div></div>";
        
        $.each(data, function(key,val){
            var at_descricao = val.Descricao;
            var at_tecnico = val.Tecnico;
            
            var aux = val.DataHoraInicio.split(" ");
            var auy = aux[0].split("-");
            aux = aux[1].split(".");
            var at_dataHoraInicio = auy[2] +"/"+auy[1]+"/"+auy[0] + " " + aux[0];
            
            var aux = val.DataHoraFinal.split(" ");
            var auy = aux[0].split("-");
            aux = aux[1].split(".");
            var at_dataHoraFinal = auy[2] +"/"+auy[1]+"/"+auy[0] + " " + aux[0];
            var at_houveVisita = val.VisitaLocal ? "Sim" : "Não";
            var at_status = val.Status;
            var at_codAt = val.CodAtendimento;
            var at_codOS = val.CodOS;
            
            atend += "<div class='det_atend'>";
            atend += "<p><span>" + at_descricao + "</span></p>";
            atend += "<p><strong>Tecnico: </strong><span>"+at_tecnico+"</span></p>";
            atend += "<p><strong>Início: </strong><span>"+at_dataHoraInicio+"</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
            atend += "<strong>Término: </strong><span>"+at_dataHoraFinal+"</span></p>";
            atend += "<p><strong>Houve visita ao local? </strong><span>"+at_houveVisita+"</span></p>";
            atend += "<p><strong>Status: </strong><span>"+at_status+"</span></p>";
            atend += "<p><button id='btnMostrarProd' class='techsec-button' onclick='mostrarProdutos("+at_codOS+","+at_codAt+")'>Produtos</button><p><div id='produtos" +at_codAt+"' class='lista-prod2'></div></p></p>";
            atend += "</div>";
        });
        atend += "</div>";
        $(".view-atendimentos").html(atend);
        
    });
}

function addOS(){
    window.location="/os/add-os";
}

function doAtend(codOS){
    window.location="/os/do-atend/" + codOS ;
}

function showNFSE(numSaida, linkNFSE){
    if($(window).width() <= 480){
        $('.techsec-menu-lateral').fadeOut();
        $('#btn-listar').show();
        $('#btn-voltar').hide();

    }
    $.getJSON('./listanfse/' + numSaida, function(data) {
        var aux = data[0].Data.split(" ");
        aux = aux[0].split("-");
        var aux1 = data[0].DataEnvio.split(" ");
        aux1 = aux1[0].split("-");       
        
        var nfse_data = aux[2]+"/"+aux[1]+"/"+aux[0];
        var nfse_dataEnvio = aux1[2]+"/"+aux1[1]+"/"+aux1[0];
        var nfse_numSaida = data[0].NumSaida;
        var nfse_numNota = data[0].NumNota;
        var nfse_valorNFSe = data[0].ValorNFSe; 
        var nfse_cancelada = data[0].Cancelada;
        
        $('.view-os-data').html("Data da venda - "+nfse_data);
        $('.view-os-title').html("[NFSE Saída - "+nfse_numSaida+"]");
        $('#cliente_os').html(nfse_dataEnvio);
        $('#tipo_os').html(nfse_numNota);
        $('#status_os').html("R$ "+nfse_valorNFSe);
        $('#prioridade_os').html(nfse_cancelada);
         
        var pdfnfse = "<div class='det_atend'>";
        pdfnfse += "<a href =\"" + linkNFSE + "\">NFSe em PDF</a>";
        pdfnfse += "</div>";
        
        $(".view-atendimentos").html(pdfnfse);
    });
}

function showBoleto(codDoc, linkNFSE){
    if($(window).width() <= 480){
        $('.techsec-menu-lateral').fadeOut();
        $('#btn-listar').show();
        $('#btn-voltar').hide();

    }

    $.getJSON('/cnab/listaboleto/' + codDoc, function(data) {
        var aux1 = data[0].DataVencimento.split(" ");
        aux1 = aux1[0].split("-");    
        
        var boleto_dataVenc = aux1[2]+"/"+aux1[1]+"/"+aux1[0];
        var boleto_numDoc = data[0].NumeroDoc;
        var boleto_valorBoleto = data[0].ValorEmAberto; 
        
        $('#cliente_os').html(boleto_numDoc);
        $('#tipo_os').html(boleto_dataVenc);
        $('#status_os').html("R$ "+boleto_valorBoleto);
        
        var nfse = '';
        
        if (linkNFSE != '')
            nfse = "<a href =\"" + linkNFSE + "\">Gerar NFSe</a>&nbsp;|";

        var pdfnfse = "<div class='det_atend'>";
        pdfnfse += "|&nbsp;<a href =\"" + 'cnab/gerar-boleto/' + codDoc + "\">Gerar Boleto</a>&nbsp;|&nbsp;"+nfse;
        
        $(".view-atendimentos").html(pdfnfse);
    });
}

function showProvAlarme(NoMsgProv){
    if($(window).width() <= 480){
        $('.techsec-menu-lateral').fadeOut();
        $('#btn-listar').show();
        $('#btn-voltar').hide();
    }
    $.getJSON('/alarme/providencia/' + NoMsgProv, function(data) {
        var providencia = data.providencia;
        var audio = data.audio;
        
        $('.view-os-data').html("");
        $('.view-os-title').html("[Providências/Retornos]");
        
        var prov = "";
        
        prov += "<div class=\"techsec-container-alarme\">";
        prov += "<input type=\"button\" class=\"techsec-button\" onclick=showVideos("+NoMsgProv+") value=\"Vídeos\"/>";
        prov += "</div>";
        
        $.each(providencia, function(key, val){
            var pr_providencia = val.Providencia;
            var pr_contato = val.Contato;
            var aux = val.DataServidor.split(" ");
            var auy = aux[0].split("-");
            aux = aux[1].split(".");
            var pr_dataServidor = auy[2] +"/"+auy[1]+"/"+auy[0];
            var pr_horaServidor = aux[0];
            var pr_noTomadaProv = val.NoTomadaProv;
            var pr_noProvidencia = val.NoProvidencia;
            
            prov += "<div class=\"techsec-container-alarme\">";
            prov += "<div class=\"techsec-tabela-alarme\">";
            prov += "<p><strong><div class=\"techsec-dados-tabela-alarme-data\">"+pr_dataServidor+" - "+pr_horaServidor+"</div></strong></p>";
            prov += "<p><strong><div class=\"techsec-dados-tabela-alarme-contato\">"+pr_contato+"</div></strong></p>";
            prov += "<div class=\"techsec-dados-tabela-alarme-select\">";
            prov += "<p><strong><select name='select' id='"+pr_noTomadaProv+"' onchange='changePlayerFile("+pr_noTomadaProv+")' class=\"techsec-select\">";
            prov += "<option value='1'>Gravações</option>";
            
            $.each(audio, function(akey, aval){
                var au_noTomadaProv = aval.NoTomadaProv;
                var au_noProvidencia = aval.NoProvidencia;
                var au_nome = aval.Nome;
                var aux = aval.Data.split(" ");
                var auy = aux[0].split("-");
                    aux = aux[1].split(".");
                var au_data = auy[2] +"/"+auy[1]+"/"+auy[0] + " " + aux[0];
                var au_descricao = aval.Descricao;
                var au_telefone = aval.Telefone;
                 
                if(au_noTomadaProv == pr_noTomadaProv && au_noProvidencia == pr_noProvidencia){
                    prov += "<option value='http://zendserverdevel/FileManager_Atualizacao/Audio/" + au_nome + "' >"+ au_data+" - "+au_descricao+" - "+au_telefone+"</option>";
                }
            });

            prov += "</select></strong></p></div>";
            prov += "<div class=\"techsec-dados-tabela-alarme-audio\"><audio id='player"+ pr_noTomadaProv+"' src='' controls='controls' class=\"techsec-audio\"></audio></div>";
            prov += "</div>";
            prov += "<div class=\"techsec-dados-tabela-alarme-prov\"><strong>Providência: </strong>"+pr_providencia+"</div>";
            prov += "<br>";
            prov += "<hr>";
            prov += "</div>";
        });
        
        $(".view-detalhe-os").html(prov);
               
    });
}

function showVideos(NoMsgProv){
    $.getJSON('/alarme/video/' + NoMsgProv, function(data) {
        
        $('.view-os-data').html("");
        $('.view-os-title').html("[Vídeos]");
                
        var video= '';
        
        video += "<div class=\"techsec-container-alarme\">";
        video += "<div class=\"techsec-tabela-alarme\">";
        video += "<div class=\"techsec-dados-tabela-alarme-data\"><p><strong>31/01/2013 - 15:56:35</strong></p></div>";
        video += "<div class=\"techsec-dados-tabela-alarme-contato\"><p><strong>CFTV [187.4.83.229]</strong></p></div>";
        video += "<div class=\"techsec-dados-tabela-alarme-select\">";
        video += "<p><strong><select name='select' id='selectvideo' onchange='changeVideoPlayerFile()' class=\"techsec-select\">";
        video += "<option value='1'>Câmeras/Vídeos</option>";
        video += "<option value='http://zendserverdevel/videos/MonitoramentoImagem_Cam1.MP4'>1 - Estacionamento</option>";
        video += "<option value='http://zendserverdevel/videos/MonitoramentoImagem_Cam3.MP4'>2 - Entrada principal</option>";
        video += "<option value='http://zendserverdevel/videos/MonitoramentoImagem_Cam4.MP4'>3 - Corredor</option>";
        video += "<option value='http://zendserverdevel/videos/MonitoramentoImagem_Cam5.MP4'>4 - Estacionamento</option>";
        video += "<option value='http://zendserverdevel/videos/MonitoramentoImagem_Cam6.MP4'>5 - Estacionamento</option>";
        video += "</select></strong></p></div>";
        video += "</div>";
        video += "<div><video controls='' name='videoplayer' id='videoplayer'><source src='' type='video/mp4'></video></div>";
        video += "<br>";
        video += "<hr>";
        video += "</div>";
        
        $(".view-detalhe-os").html(video);
               
    });
}

function changePlayerFile(NoTomadaProv)    
{ 
    var player = document.getElementById('player'+NoTomadaProv);
    player.src = document.getElementById(NoTomadaProv).value;
}

function changeVideoPlayerFile()    
{ 
    videoplayer.src = document.getElementById('selectvideo').value;
}

function enviaBoleto(codDoc)
{
     if($(window).width() <= 480){
        $('.techsec-menu-lateral').fadeOut();
        $('#btn-listar').show();
        $('#btn-voltar').hide();

    }

    $.getJSON('/cnab/email-boleto/' + codDoc, function(data) {
       alert(data);
    });
}

function saveAtend(codOS){
    var chegada = document.getElementById('txt_dataChegada').value;
    var saida = document.getElementById('txt_dataSaida').value;
    var descricao = document.getElementById('txt_descricao').value;
    var status = document.getElementById('txt_status').value;
    var visita = document.getElementById('ck_visita').value;
    
   
    $.post( '/os/save-atendimento/' + codOS, 
            {'txt_dataChegada' : chegada,
             'txt_dataSaida' : saida,
             'txt_descricao' : descricao,
             'txt_status' : status,
             'ck_visita' : visita
            }, 
            function(data){
                if(data.sucesso){
                    $('.lista-prod').fadeIn('fast');
                    $('#btn-add-prod').attr('onclick', 'buscaProd('+data.CodOS+','+data.CodAtendimento+')');
                    $('#btnSalvar').attr('onclick', 'voltarLstOS()');
                }               
           }, 
           "json"
    );
}

function buscaProd(codOS, codAtendimento){
    var id = codOS+'at'+codAtendimento;
    window.location = '/os/buscar-prod/' + id;
}

function filterProd(){
    $('#txt-busca-prod').val(function(index, value){
        if(value.length > 0){
            value = btoa(value);
        }else{
            value = 0;
        }
               
        $.getJSON('/os/filter-prod/'+value, function(data){
            var result = "";
            
            $.each(data, function(key, value){
                result += "<div class='lst_prod_item'><input type='radio' id='prod_item' name='prod_item' value='" + value.Cod_pro + "'>"+value.Especificacao+"</div>";
            });
            $("#lista-result-prod").html(result);
        });
    });
}

function addProd(codOS, codAt){
    var cod_prod = 0;
    var quantidade = 0;
    
    $('#prod_item:checked').val(function(index, value){
        cod_prod = value;
    });
    
    $('#txt_quantidade').val(function(index, value){
        quantidade = value;
    });
    
    $.post('/os/add-prod/'+ codOS, 
           {
             'codAt'      : codAt,
             'cod_pro'    : cod_prod,
             'quantidade' : quantidade
           }, function(data){
      
               window.location = '/os/do-atend/'+codOS + 'at' + codAt
           });   
}

function mostraListaProd(CodOS, CodAt){
    $('.lista-prod').show();
    $('#btn-add-prod').attr('onclick', 'buscaProd('+CodOS+','+CodAt+')');
}

function showMap(codOS){
    window.location = '/os/show-map/'+codOS;
}

function cancelar(){
    history.back(1);
}

function voltarLstOS(){
    window.location = '/os/buscar-os';
}

function mostrarProdutos(codOS,codAtend){
   var id = codOS+"at"+codAtend;
 
   $.getJSON('/os/show-prod/' + id, function(data){
       
       var inner = "<div class='header'><div id='prod'>Produto</div><div id='qtde'>Quantidade</div></div>";
       $.each(data, function(key, val){
           inner += "<div class='detail'><div id='prod'>"+val.Especificacao+"</div><div id='qtde'>"+val.Quantidade+"</div></div>";
       });
       
       var sel = "#produtos"+codAtend;
       
       $(sel).html(inner);
       
   });
}