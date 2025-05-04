<template>
  <div v-if="products && products.length > 0" class="productos-wrapper">
    <table>
      <thead>
        <tr>
          <th @click="sort('name')">Nombre <span v-if="sortKey==='name'">{{ sortDir==='asc'?'▲':'▼' }}</span></th>
          <th @click="sort('price')">Precio <span v-if="sortKey==='price'">{{ sortDir==='asc'?'▲':'▼' }}</span></th>
          <th v-if="userRole==='admin'">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in sortedProducts" :key="product.id">
          <td>{{ product.name }}</td>
          <td>{{ product.price }} €</td>
          <td v-if="userRole==='admin'" class="table-actions">
            <button @click="emit('edit', product)">Editar</button>
            <button @click="deleteProduct(product.id)" class="button-red">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="error" class="mensaje-error">
      {{ error }}
    </div>
  </div>
  <div v-else-if="userRole && products && products.length === 0" class="productos-wrapper mensaje-vacio">
    <p>No hay productos para mostrar.</p>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import axios from 'axios';

const error = ref('');

const props = defineProps({
  products: Array,
  userRole: String,
  apiToken: String,
});

const sortKey = ref('name');
const sortDir = ref('asc');

function sort(key) {
  if (sortKey.value === key) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortDir.value = 'asc';
  }
}

const sortedProducts = computed(() => {
  return [...props.products].sort((a, b) => {
    let cmp = 0;
    if (sortKey.value === 'name') {
      cmp = a.name.localeCompare(b.name);
    } else if (sortKey.value === 'price') {
      cmp = a.price - b.price;
    }
    return sortDir.value === 'asc' ? cmp : -cmp;
  });
});

const emit = defineEmits(['refresh', 'edit']);

async function deleteProduct(id) {
  error.value = '';


  try {
    await axios.delete(`http://127.0.0.1:8000/api/products/${id}`, {
      headers: {
        Authorization: `Bearer ${props.apiToken}`
      }
    });
    // Notifiquem al pare que refresqui
    emit('refresh');
  } catch (e) {
    console.error('Error eliminando producto:', e);
  }
}

// Permet que el pare pugui refrescar la taula
watch(() => props.products, () => {}, { deep: true });
</script>
