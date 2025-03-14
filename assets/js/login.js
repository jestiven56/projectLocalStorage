document.addEventListener('DOMContentLoaded', function() {
  // Elementos del DOM
  const loginForm = document.getElementById('loginForm');
  const welcomeScreen = document.getElementById('welcomeScreen');
  const form = document.getElementById('form');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const emailError = document.getElementById('emailError');
  const passwordError = document.getElementById('passwordError');
  const alertMessage = document.getElementById('alertMessage');
  const loginButton = document.getElementById('loginButton');
  const logoutButton = document.getElementById('logoutButton');
  const welcomeMessage = document.getElementById('welcomeMessage');

  // API URLs
  const LOGIN_URL = 'api/login.php';
  const CHECK_SESSION_URL = 'api/check_session.php';
  const LOGOUT_URL = 'api/logout.php';
  const USER_URL = 'api/create_user.php';

  // Función para validar email
  function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
  }

  // Función para validar contraseña
  function isValidPassword(password) {
      return password.length >= 6;
  }

  // Función para mostrar error
  function showError(element, message) {
      element.textContent = message;
      element.style.display = 'block';
  }

  // Función para ocultar error
  function hideError(element) {
      element.textContent = '';
      element.style.display = 'none';
  }

  // Función para mostrar alerta
  function showAlert(type, message) {
    const alertMessage = document.getElementById("alertMessage");
    const alertText = document.getElementById("alertText");

    // Establecer el tipo de alerta
    alertMessage.classList.remove("alert-success", "alert-danger");
    alertMessage.classList.add(`alert-${type}`);

    // Establecer el mensaje de error
    alertText.textContent = message;

    // Mostrar la alerta quitando 'd-none'
    alertMessage.classList.remove("d-none");

    // Ocultar la alerta automáticamente después de 3 segundos
    setTimeout(() => {
        alertMessage.classList.add("d-none");
    }, 3000);
  }

  // Función para mostrar pantalla de bienvenida
  function showWelcomeScreen(email) {
    loginForm.classList.add('d-none');
    welcomeScreen.classList.remove('d-none');
    welcomeMessage.textContent = `Bienvenido, ${email}`;
  }

  // Función para mostrar pantalla de login
  function showLoginScreen() {
    loginForm.classList.remove('d-none');
    welcomeScreen.classList.add('d-none');
    form.reset();
  }

  // Función para iniciar sesión con fetch
  async function login(email, password) {
      try {
          const response = await fetch(LOGIN_URL, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify({ email, password })
          });

          const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Error en el inicio de sesión');
          }

          // Si la respuesta es exitosa, guardar el usuario en localStorage
          localStorage.setItem('email', data.email);
          return data.email;

      } catch (error) {
          throw error;
      }
  }

  // Función para verificar sesión existente
  async function checkSession() {
      try {
          // Primero verificamos si hay un indicador en localStorage
          if (!localStorage.getItem('email')) {
              return false;
          }

          const response = await fetch(CHECK_SESSION_URL, {
              method: 'GET',
              headers: {
                  'Content-Type': 'application/json'
              }
          });

          const data = await response.json();

          if (!response.ok || !data.loggedIn) {
              // Si no hay sesión válida, limpiar localStorage
              localStorage.removeItem('email');
              return false;
          }

          return data.email;

      } catch (error) {
          console.error('Error al verificar sesión:', error);
          return false;
      }
  }

  // Función para cerrar sesión
  async function logout() {
      try {
          const response = await fetch(LOGOUT_URL, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              }
          });

          const data = await response.json();

          if (!response.ok) {
              throw new Error(data.message || 'Error al cerrar sesión');
          }

          // Eliminar la sesión del localStorage
          localStorage.removeItem('email');
          showLoginScreen();

      } catch (error) {
          console.error('Error al cerrar sesión:', error);
          showAlert('danger', 'Error al cerrar sesión');
      }
  }

  // Verificar si hay una sesión activa al cargar la página
  checkSession().then(userEmail => {
      if (userEmail) {
          showWelcomeScreen(userEmail);
      }
  });

  // Validación en tiempo real del email
  emailInput.addEventListener('input', function() {
      if (this.value.trim() === '') {
          hideError(emailError);
      } else if (!isValidEmail(this.value)) {
          showError(emailError, 'Por favor, ingresa un correo electrónico válido');
      } else {
          hideError(emailError);
      }
  });

  // Validación en tiempo real de la contraseña
  passwordInput.addEventListener('input', function() {
      if (this.value.trim() === '') {
          hideError(passwordError);
      } else if (!isValidPassword(this.value)) {
          showError(passwordError, 'La contraseña debe tener al menos 6 caracteres');
      } else {
          hideError(passwordError);
      }
  });

  //enviar peticion para crear usuarios de prueba
  async function createUser(){
    try {
        const response = await fetch(USER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
          body: JSON.stringify({ email: 'user1@example.com'})
        });
      
      const data = await response.json();
      console.log(data);

      return data.message;

    } catch (error) {
      throw error;
      
    }
  }

  //cuando carga la pagina se crea un usuario de prueba
  createUser().then(message => {
    console.log(message);
  }
  ).catch(error => {
    console.error('Error al crear usuario:', error);
  }
  );

  // Manejar envío del formulario
  form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = emailInput.value.trim();
      const password = passwordInput.value.trim();
      let isValid = true;
      
      // Validar email
      if (!email) {
          showError(emailError, 'El correo electrónico es requerido');
          isValid = false;
      } else if (!isValidEmail(email)) {
          showError(emailError, 'Por favor, ingresa un correo electrónico válido');
          isValid = false;
      } else {
          hideError(emailError);
      }
      
      // Validar contraseña
      if (!password) {
          showError(passwordError, 'La contraseña es requerida');
          isValid = false;
      } else if (!isValidPassword(password)) {
          showError(passwordError, 'La contraseña debe tener al menos 6 caracteres');
          isValid = false;
      } else {
          hideError(passwordError);
      }
      
      // Si los datos son válidos, intentar login
      if (isValid) {
        loginButton.disabled = true;
        let spinnerModal = new bootstrap.Modal(document.getElementById('spinnerModal'));
        spinnerModal.show();

        login(email, password)
          .then(userEmail => {
            // Mostrar pantalla de bienvenida
            setTimeout(() => {
                showWelcomeScreen(userEmail);
                showAlert('success', '¡Inicio de sesión exitoso!');
            }
            , 1500);
            })
          .catch(error => {
              setTimeout(() => {
                  console.error('Error al iniciar sesión:', error);
                showAlert('danger', error.message);
              }, 1500);
            })
            .finally(() => {
                // Ocultar spinner y habilitar botón después del proceso
                loginButton.disabled = false;
                setTimeout(() => {
                  spinnerModal.hide();
                }, 1500);
            });
      }

  });

  // Manejar cierre de sesión
  logoutButton.addEventListener('click', logout);
});