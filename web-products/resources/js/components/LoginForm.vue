<template>
<div class="login-dark">
    <form @submit.prevent="login">
      <h2>Iniciar Sesión</h2>
      <div>
        <label>Email: </label>
        <input v-model="email" type="email" required />
      </div>
      <div>
        <label>Contraseña: </label>
        <input id="password" type="password" v-model="password" required />
      </div>
      <button type="submit">Entrar</button>
      <div v-if="error">{{ error }}</div>
      <hr>
      <div>
        <strong>Credenciales de prueba:</strong>
        <p>Email: admin@example.com</p>
        <p>Contraseña: password</p>
      </div>
      <button type="button" @click="$emit('show-register')" class="register-btn">¿No tienes cuenta? Regístrate</button>
    </form>
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
    error.value = 'Credencials incorrectas';
  }
}
</script>
