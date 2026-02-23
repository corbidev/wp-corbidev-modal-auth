jQuery(function($){

function openModal(){ $('#wpma-modal').removeClass('hidden').addClass('flex'); }
function closeModal(){ $('#wpma-modal').addClass('hidden').removeClass('flex'); }

const $wpmaMessage = $('#wpma-message');
const $wpmaMessageText = $('#wpma-message-text');

function hideWpmaMessage(){
  $wpmaMessage.addClass('hidden');
  $wpmaMessageText.html('');
}

function showWpmaError(msg){
  const text = msg || 'Une erreur est survenue, veuillez réessayer.';
  $wpmaMessage.removeClass('hidden');
  $wpmaMessageText.html(text);
}

function removeWpmaLoginParam(){
  try{
    var url = new URL(window.location.href);
    if(!url.searchParams.has('wpma_login')) return;
    url.searchParams.delete('wpma_login');
    window.history.replaceState({}, '', url.pathname + url.search + url.hash);
  }catch(e){
    // En cas d'absence de support URL, on ne fait rien
  }
}

$('.wpma-open').on('click', function(){
  hideWpmaMessage();
  openModal();
});

$('#wpma-close').on('click', function(){
  closeModal();
});

$('#wpma-message-close').on('click', function(){
  hideWpmaMessage();
});

$('.wpma-float-logout').on('click', function(){
  $.post(WPMA.ajax,{
    action:'wpma_logout',
    nonce:WPMA.nonce
  },function(r){
    if(r && r.success){
      location.reload();
    }else{
      showWpmaError('Une erreur est survenue lors de la déconnexion.');
      openModal();
    }
  });
});

$('#wpma-submit').on('click', ()=>{
  hideWpmaMessage();
  $.post(WPMA.ajax,{
    action:'wpma_login',nonce:WPMA.nonce,
    login:$('#wpma-login').val(),
    password:$('#wpma-password').val(),
    remember:$('#wpma-remember').is(':checked') ? 1 : 0
  },r=>{
    if(r && r.success){
      location.reload();
    }else{
      // Message volontairement générique pour ne pas exposer
      // les détails des erreurs WordPress à l'utilisateur final.
      showWpmaError('Identifiant ou mot de passe incorrect.');
    }
  });
});

const $wpmaStatus = $('#wpma-status');

$('#wpma-forgot-submit').on('click', ()=>{
  const email = $('#wpma-email').val().trim();
  $wpmaStatus.removeClass('hidden text-emerald-400 text-red-400').text('');

  if(!email){
    $wpmaStatus
      .addClass('text-red-400')
      .text('Veuillez saisir votre adresse e-mail.');
    return;
  }

  $.post(WPMA.ajax,{
    action:'wpma_forgot',nonce:WPMA.nonce,
    email:email
  },r=>{
    if(r.success){
      $wpmaStatus
        .removeClass('text-red-400')
        .addClass('text-emerald-400')
        .text('Email envoyé, vérifiez votre boîte de réception.');
    }else{
      $wpmaStatus
        .removeClass('text-emerald-400')
        .addClass('text-red-400')
        .text(r.data || 'Une erreur est survenue, veuillez réessayer.');
    }
  });
});

$('.wpma-link').on('click', function(){
  let a=$(this).data('action');
  hideWpmaMessage();
  $('#wpma-login-form,#wpma-forgot-form').addClass('hidden');
  if(a==='forgot'){
    $('#wpma-title').text('Mot de passe oublié');
    $('#wpma-forgot-form').removeClass('hidden');
  }else{
    $('#wpma-title').text('Connexion');
    $('#wpma-login-form').removeClass('hidden');
  }
});

$('#wpma-modal').on('click', e=>{
  if(e.target.id==='wpma-modal') closeModal();
});

// Ouverture automatique du modal si indiquée par le backend
if (typeof WPMA !== 'undefined' && WPMA.auto_open) {
  hideWpmaMessage();
  openModal();
  removeWpmaLoginParam();
}

});
