let auth0 = null;

const fetchAuthConfig = () => fetch("/auth_config.json");

const configureClient = async () => {
  const response = await fetchAuthConfig();
  const config = await response.json();

  auth0 = await createAuth0Client({
    domain: config.domain,
    client_id: config.clientId
  });
};

const updateUI = async () => { 
  const isAuthenticated = await auth0.isAuthenticated();

  document.getElementById("btn-logout").disabled = !isAuthenticated;
  document.getElementById("btn-login").disabled = isAuthenticated;
  
  if (isAuthenticated) {
    document.getElementById("gated-content").classList.remove("hidden");

    var token = await auth0.getTokenSilently();
    var user = await auth0.getUser();

    $('#token').val(token);

    $("#gated-content").css("display", "block");
    $("#ipt-user-profile").text(user['nickname']);

    $('#user').val(JSON.stringify(user))
    
    $('#btn-login').css("display", "none");
    $('#btn-logout').css("display", "block");

    console.log('Está autenticado!');

  } else {
    $("#gated-content").css("display", "none");

    console.log('No está autenticado!');
  }
};

const login = async () => {
  await auth0.loginWithRedirect({
    redirect_uri: window.location.origin
  });
};

const logout = () => {
  auth0.logout({
    returnTo: window.location.origin
  });
};

window.onload = async () => {
  await configureClient();

  updateUI();

  const isAuthenticated = await auth0.isAuthenticated();

  if (isAuthenticated) {
    return;
  }

  const query = window.location.search;
  if (query.includes("code=") && query.includes("state=")) {

    await auth0.handleRedirectCallback();
    
    updateUI();

    window.history.replaceState({}, document.title, "/");
  }

};
