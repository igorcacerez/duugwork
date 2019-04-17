
var Dados = {
  "pgCurso": "apresentacao",
  "subMenu": "fechado"
} 


// Gera o  menu fixo
$(window).scroll(function(){

    var atual = $(document).scrollTop();
  
    if(atual >= 35)
    {
      $('#menuHeader').addClass('fixo');
      $("#menuFixo").css("display","block");
    }
    else
    {
      $('#menuHeader').removeClass('fixo');
      $("#menuFixo").css("display","none");
    }
  
});



// Slide da Home
$('#sld_bannerHome').owlCarousel({
    items: 1,
    loop: true,
    margin: 0,
    nav: false,
    autoplay: true,
    autoplayTimeout: 6000,
    autoplayHoverPause: true,
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
});



// Slide Logos na Home
$('#sld_logos').owlCarousel({
    loop: true,
    margin: 20,
    nav: true,
    autoplay:true,
    autoplayTimeout:6000,
    autoplayHoverPause:true,
    responsive:{
      0:{
        items:1
      },
      600:{
          items:2
      },
      700:{
        items:3
      },
      1000:{
        items:4
      }
    }
  });

$("#sld_logos .owl-prev").html('<i class="fa fa-chevron-left" aria-hidden="true"></i>');
$("#sld_logos .owl-next").html('<i class="fa fa-chevron-right" aria-hidden="true"></i>');


$(".campoCPF").mask("999.999.999-99");
$(".campoCEP").mask("99999-999");
$(".campoCELULAR").mask("(99) 99999-9999");




function modais(acao, modal) 
{
  if(acao == "abre")
  {
    $("#" + modal).fadeIn();
  } 
  else 
  {
    $("#" + modal).fadeOut();
  } 
}


function pagCurso(pag) 
{
  if(Dados.pgCurso != pag)
  {

    document.getElementById("bt_"+ Dados.pgCurso).className = "";
    document.getElementById("bt_"+ pag).className = "ativo";


    $("#blo_" + Dados.pgCurso).css("display","none");
    $("#blo_" + pag).css("display","block");

    Dados.pgCurso = pag;
  }
}


function subMenu() 
{
  // Verifica o que é para fazer 
  if(Dados.subMenu == "fechado")
  {
    $("#cursoSubMenu").css("left","0px");
    Dados.subMenu = "aberto";
  }  
  else 
  {
    $("#cursoSubMenu").css("left","-230px");
    Dados.subMenu = "fechado";
  }
}



function acaoMenu(tipo) 
{
  if(tipo == "fecha")
  {
    $("#menuResp").css("left","-300px");
    $("#fundoMenu").fadeOut();
  } 
  else 
  {
    $("#menuResp").css("left","-0px");
    $("#fundoMenu").fadeIn();

  } 
}