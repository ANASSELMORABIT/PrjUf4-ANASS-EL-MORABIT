<template>
  <div>

    <login-form v-if="!isAuthenticated && !showRegister" @login-success="onLoginSuccess" @show-register="showRegister = true" />
    <register-form v-if="!isAuthenticated && showRegister" @register-success="showRegister = false" @show-login="showRegister = false" />
    <div v-else>

      <product-table
        :products="products"
        :user-role="userRole"
        :api-token="apiToken"
        @refresh="fetchProducts"
        @edit="onEditProduct"
      />
      <div class="action-bar">
        <button v-if="userRole === 'admin'" class="btn-add" @click="showCreateForm = true; editingProduct = null">Añadir producto</button>
      </div>
      <div class="action-bar" v-if="isAuthenticated">
        <button class="btn-logout" @click="logout">Cerrar sesión</button>
      </div>

      <div v-show="showCreateForm" class="crear-producto-form dark-card">
        <h3>Crear nuevo producto</h3>
        <div class="form-group">
          <label>Nombre: </label>
          <input v-model="newProduct.name" required />
        </div>
        <div class="form-group">
          <label>Precio: </label>
          <input v-model.number="newProduct.price" type="number" step="0.01" required />
        </div>
        <div class="form-group">
          <label>Descripción: </label>
          <input v-model="newProduct.description" required />
        </div>
        <div class="form-group">
          <label>Stock: </label>
          <input v-model.number="newProduct.stock" type="number" required />
        </div>
        <button @click="createProduct">Crear producto</button>
        <button @click="showCreateForm = false" class="button-cancel">Cancelar</button>
        <div v-if="createError" class="mensaje-error">{{ createError }}</div>
      </div>

      <div v-show="editingProduct" class="editar-producto-form dark-card">
        <h3>Editar producto</h3>
        <div class="form-group">
          <label>Nombre: </label>
          <input v-model="editProductData.name" required />
        </div>
        <div class="form-group">
          <label>Precio: </label>
          <input v-model.number="editProductData.price" type="number" step="0.01" required />
        </div>
        <div class="form-group">
          <label>Descripción: </label>
          <input v-model="editProductData.description" required />
        </div>
        <div class="form-group">
          <label>Stock: </label>
          <input v-model.number="editProductData.stock" type="number" required />
        </div>
        <button @click="updateProduct">Guardar</button>
        <button @click="() => { editingProduct = null; editError = ''; }" class="button-cancel">Cancelar</button>
        <div v-if="editError" class="mensaje-error">{{ editError }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>

function logout() {
  isAuthenticated.value = false;
  apiToken.value = '';
  userRole.value = '';
  products.value = [];
  editingProduct.value = null;
  showCreateForm.value = false;
}

import { ref } from 'vue';
import axios from 'axios';
import LoginForm from './LoginForm.vue';
import RegisterForm from './RegisterForm.vue';
import ProductTable from './ProductTable.vue';
import ProductForm from './ProductForm.vue';

const isAuthenticated = ref(false);
const showRegister = ref(false);
const apiToken = ref('');
const userRole = ref('');
const products = ref([]);
const editingProduct = ref(null);
const showCreateForm = ref(false);
const editProductData = ref({ name: '', price: '', description: '', stock: '' });
const editError = ref('');

const newProduct = ref({
  name: '',
  price: '',
  description: '',
  stock: ''
});
const createError = ref('');

async function createProduct() {
  createError.value = '';
  if (!newProduct.value.name || !newProduct.value.price || !newProduct.value.description) {
    createError.value = 'Por favor, rellena todos los campos.';
    return;
  }
  try {
    await axios.post('http://127.0.0.1:8000/api/products', newProduct.value, {
      headers: {
        Authorization: `Bearer ${apiToken.value}`
      }
    });
    newProduct.value = { name: '', price: 0, description: '', stock: 0 };
    fetchProducts();
    showCreateForm.value = false;
  } catch (e) {
    if (e.response && e.response.data && e.response.data.message) {
      createError.value = e.response.data.message;
    } else if (e.message) {
      createError.value = 'Error creando producto: ' + e.message;
    } else {
      createError.value = 'Error creando producto';
    }
  }
}


function onLoginSuccess(response) {
  // Permite tanto destructuring como objeto response para máxima compatibilidad
  let token = response.token;
  let role = response.role;
  if (response.data) {
    // Axios devuelve .data si es llamada directa
    token = response.data.token;
    role = response.data.role;
  }
  apiToken.value = token;
  userRole.value = role;
  isAuthenticated.value = true;
  fetchProducts();
}

async function fetchProducts() {
  if (!apiToken.value) return;
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/products', {
      headers: {
        Authorization: `Bearer ${apiToken.value}`
      }
    });
    products.value = response.data;
    if (editingProduct.value) {
      const stillExists = response.data.some(p => p.id === editingProduct.value.id);
      if (!stillExists) {
        editingProduct.value = null;
        editError.value = '';
      }
    }
  } catch (e) {
    products.value = [];
  }
}

function onEditProduct(product) {
  showCreateForm.value = false;
  editingProduct.value = product;
  editProductData.value = { ...product };
  editError.value = '';
}

async function updateProduct() {
  editError.value = '';
  if (!editProductData.value.name || !editProductData.value.price || !editProductData.value.description) {
    editError.value = 'Por favor, rellena todos los campos.';
    return;
  }
  try {
    await axios.put(`http://127.0.0.1:8000/api/products/${editProductData.value.id}`, editProductData.value, {
      headers: {
        Authorization: `Bearer ${apiToken.value}`
      }
    });
    editingProduct.value = null;
    fetchProducts();
  } catch (e) {
    if (e.response && e.response.data && e.response.data.message) {
      editError.value = e.response.data.message;
    } else if (e.message) {
      editError.value = 'Error editando producto: ' + e.message;
    } else {
      editError.value = 'Error editando producto';
    }
  }
}

function onEditSuccess() {
  editingProduct.value = null;
  fetchProducts();
}
</script>
