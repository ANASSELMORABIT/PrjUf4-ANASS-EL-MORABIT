<template>
  <div class="register-container">
    <form class="register-card" @submit.prevent="register">
      <h2 class="register-title">Crear Cuenta</h2>

      <div class="form-group">
        <label>Email</label>
        <input v-model="email" type="email" required class="form-input" />
      </div>

      <div class="form-group">
        <label>Nombre</label>
        <input v-model="name" type="text" required class="form-input" />
      </div>

      <div class="form-group">
        <label>Contraseña</label>
        <input v-model="password" type="password" required class="form-input" />
      </div>

      <div class="form-group">
        <label>Confirmar Contraseña</label>
        <input v-model="password_confirmation" type="password" required class="form-input" />
      </div>

      <button type="submit" class="btn-submit">Registrarse</button>

      <div v-if="error" class="form-error">{{ error }}</div>
      <div v-if="success" class="form-success">¡Registro exitoso! Ahora puedes iniciar sesión.</div>

      <button type="button" @click="$emit('show-login')" class="btn-switch">¿Ya tienes cuenta? Inicia sesión</button>
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


<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 3rem;
  background: #f0f2f5;
  min-height: 100vh;
}

.register-card {
  background: white;
  padding: 2rem 2.5rem;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

.register-title {
  margin-bottom: 1.5rem;
  font-size: 1.8rem;
  font-weight: 700;
  text-align: center;
  color: #333;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-size: 0.95rem;
  margin-bottom: 0.5rem;
  color: #555;
}

.form-input {
  width: 100%;
  padding: 0.6rem 0.9rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 0.95rem;
  transition: border-color 0.3s;
}

.form-input:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.15);
}

.btn-submit {
  width: 100%;
  padding: 0.75rem;
  background-color: #007bff;
  border: none;
  color: white;
  font-size: 1rem;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 0.5rem;
  transition: background 0.3s;
}

.btn-submit:hover {
  background-color: #0056b3;
}

.btn-switch {
  width: 100%;
  padding: 0.6rem;
  background: transparent;
  border: none;
  color: #007bff;
  margin-top: 1rem;
  font-size: 0.9rem;
  cursor: pointer;
  text-decoration: underline;
}

.form-error {
  color: #e74c3c;
  margin-top: 0.8rem;
  font-size: 0.9rem;
  text-align: center;
}

.form-success {
  color: #28a745;
  margin-top: 0.8rem;
  font-size: 0.9rem;
  text-align: center;
}
</style>