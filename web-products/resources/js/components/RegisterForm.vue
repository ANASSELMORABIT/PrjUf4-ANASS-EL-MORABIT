<template>
<div class="login-dark">
    <form @submit.prevent="register">
      <h2>Crear Cuenta</h2>
      <div>
        <label>Email: </label>
        <input v-model="email" type="email" required />
      </div>
      <div>
        <label>Nombre: </label>
        <input v-model="name" type="text" required />
      </div>
      <div>
        <label>Contraseña: </label>
        <input v-model="password" type="password" required />
      </div>
      <div>
        <label>Confirmar Contraseña: </label>
        <input v-model="password_confirmation" type="password" required />
      </div>
      <button type="submit">Registrarse</button>
      <div v-if="error" class="error">{{ error }}</div>
      <div v-if="success" class="success">¡Registro exitoso! Ahora puedes iniciar sesión.</div>
      <button type="button" @click="$emit('show-login')" class="register-btn">¿Ya tienes cuenta? Inicia sesión</button>
    </form>
</div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['register-success', 'show-login']);
const email = ref('');
const name = ref('');
const password = ref('');
const password_confirmation = ref('');
const error = ref('');
const success = ref(false);

async function register() {
  error.value = '';
  success.value = false;
  if (password.value !== password_confirmation.value) {
    error.value = 'Las contraseñas no coinciden';
    return;
  }
  try {
    await axios.post('http://127.0.0.1:8000/api/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });
    success.value = true;
    emit('register-success');
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al registrar';
  }
}
</script>


