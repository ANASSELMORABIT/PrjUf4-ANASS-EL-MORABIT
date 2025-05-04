// Importa los estilos CSS del archivo styles.css para aplicar el diseño global de la aplicación.
import '../css/styles.css';

// Importa el archivo de configuración de Bootstrap para incluir estilos y funcionalidades de Bootstrap en la aplicación.
import './bootstrap';

// Importa la función createApp de Vue para crear una instancia de la aplicación Vue.
import { createApp } from 'vue';

// Importa los componentes Vue que se van a utilizar en la aplicación. Estos son componentes reutilizables de la interfaz de usuario.
import ProductApp from './components/ProductApp.vue';
import LoginForm from './components/LoginForm.vue';
import ProductTable from './components/ProductTable.vue';
import ProductForm from './components/ProductForm.vue';

// Crea una instancia de la aplicación Vue utilizando la función createApp.
const app = createApp({});

// Registra los componentes importados en la aplicación Vue para que estén disponibles en las vistas.
// Los componentes se registran bajo un nombre específico que se utilizará en el HTML (como etiquetas personalizadas).
app.component('product-app', ProductApp);   // Registra el componente ProductApp con el nombre 'product-app'.
app.component('login-form', LoginForm);     // Registra el componente LoginForm con el nombre 'login-form'.
app.component('product-table', ProductTable); // Registra el componente ProductTable con el nombre 'product-table'.
app.component('product-form', ProductForm);  // Registra el componente ProductForm con el nombre 'product-form'.

// Monta la aplicación Vue en un elemento HTML con el id 'app', lo que permite que Vue controle ese elemento y sus hijos.
app.mount('#app');
