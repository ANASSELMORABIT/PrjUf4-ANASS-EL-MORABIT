<template>
  <div class="login-container">
    <!-- ✅ Credenciales fuera del login-box, arriba a la derecha -->
    <div class="demo-credentials">
      <h3>🧪 Acceso de demostración</h3>
      <p><strong>Email:</strong> admin@example.com</p>
      <p><strong>Contraseña:</strong> password</p>
    </div>

    <div class="login-box">
      <form @submit.prevent="login">
        <h2>Iniciar sesión</h2>
        <div>
          <label for="email">Correo electrónico</label>
          <input v-model="email" type="email" id="email" required />
        </div>
        <div>
          <label for="password">Contraseña</label>
          <input v-model="password" type="password" id="password" required />
        </div>
        <button type="submit" class="login-btn">Entrar</button>
        <div v-if="error" class="error-message">{{ error }}</div>
        <hr />
        <button type="button" @click="$emit('show-register')" class="register-btn">
          ¿No tienes cuenta? Regístrate
        </button>
      </form>
    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['login-success', 'show-register']);
const email = ref('');
const password = ref('');
const error = ref('');

async function login() {
  error.value = '';
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/login', {
      email: email.value,
      password: password.value,
    });
    emit('login-success', { token: response.data.token, role: response.data.role });
  } catch (e) {
    error.value = 'Credenciales incorrectas';
  }
}
</script>


