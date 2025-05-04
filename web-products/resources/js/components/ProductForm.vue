<template>
  <div>
    <form @submit.prevent="save">
      <button type="button" @click="$emit('close')">✖</button>
      <h3>{{ props.editMode ? 'Editar producto' : 'Añadir producto' }}</h3>
      <div class="mb-3">
        <label>Nombre</label>
        <input v-model="name" required />
      </div>
      <div class="mb-3">
        <label>Precio</label>
        <input v-model.number="price" type="number" step="0.01" required />
      </div>
      <div class="mb-3">
        <label>Descripción</label>
        <input v-model="description" required />
      </div>
      <div class="mb-3">
        <label>Stock</label>
        <input v-model.number="stock" type="number" min="0" required />
      </div>
      <button type="submit">Guardar</button>
      <div v-if="error">{{ error }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  apiToken: String,
  initialProduct: Object,
  editMode: Boolean
});
const emit = defineEmits(['close', 'refresh']);

const name = ref(props.initialProduct ? props.initialProduct.name : '');
const price = ref(props.initialProduct ? props.initialProduct.price : 0);
const description = ref(props.initialProduct ? props.initialProduct.description : '');
const stock = ref(props.initialProduct ? props.initialProduct.stock : 0);
const error = ref('');

async function save() {
  error.value = '';
  try {
    if (props.editMode && props.initialProduct && props.initialProduct.id) {
      await axios.put(`http://127.0.0.1:8000/api/products/${props.initialProduct.id}`, {
        name: name.value,
        price: price.value,
        description: description.value,
        stock: stock.value,
      }, {
        headers: {
          Authorization: `Bearer ${props.apiToken}`
        }
      });
    } else {
      await axios.post('http://127.0.0.1:8000/api/products', {
        name: name.value,
        price: price.value,
        description: description.value,
        stock: stock.value,
      }, {
        headers: {
          Authorization: `Bearer ${props.apiToken}`
        }
      });
    }
    emit('refresh');
    emit('close');
  } catch (e) {
    // Muestra el mensaje real de la API si existe
    if (e.response && e.response.data && e.response.data.message) {
      error.value = e.response.data.message;
    } else {
      error.value = 'Error guardando producto';
    }
  }
}
</script>
