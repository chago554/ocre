import './bootstrap';
import { Chart, registerables } from 'chart.js';
import Alpine from 'alpinejs';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';
import Swal from 'sweetalert2';

window.Alpine = Alpine;

Alpine.start();

Chart.register(...registerables);
window.Chart = Chart;
window.Toastify = Toastify;

// Toast
window.Toast = {
    success: (msg) => Toastify({
        text: msg,
        duration: 3000,
        gravity: "top",
        position: "right",
        style: {
            background: "#05a040",
            borderRadius: "8px",
            fontFamily: "sans-serif",
            fontSize: "14px",
            boxShadow: "0 4px 12px rgba(232,160,32,0.3)"
        }
    }).showToast(),

    error: (msg) => Toastify({
        text: msg,
        duration: 3000,
        gravity: "top",
        position: "right",
        style: {
            background: "#c0392b",
            borderRadius: "8px",
            fontSize: "14px",
        }
    }).showToast(),

    info: (msg) => Toastify({
        text: msg,
        duration: 3000,
        gravity: "top",
        position: "right",
        style: {
            background: "#555",
            borderRadius: "8px",
            fontSize: "14px",
        }
    }).showToast(),
};

// Tema personalizado OCRE
const OcreSwal = Swal.mixin({
    confirmButtonColor: '#E6AD56',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Confirmar',
    cancelButtonText: 'Cancelar',
    borderRadius: '12px',
    customClass: {
        popup: 'ocre-swal-popup',
        title: 'ocre-swal-title',
        confirmButton: 'ocre-swal-confirm',
        cancelButton: 'ocre-swal-cancel',  
        actions: 'ocre-swal-actions',    
    }
});

window.OcreSwal = OcreSwal;

window.Modal = {
    delete: (mensaje = '¿Estás seguro?', detalle = 'Esta acción no se puede deshacer.') =>
        OcreSwal.fire({
            title: mensaje,
            text: detalle,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#c0392b',
        }),

    deleteAccount: (formAction, csrfToken) =>
        OcreSwal.fire({
            title: '¿Eliminar tu cuenta?',
            html: '<p style="font-size:14px;color:#6b7280;margin-bottom:12px;">Esta acción es irreversible. Ingresa tu contraseña para confirmar.</p>',
            input: 'password',
            inputPlaceholder: 'Ingresa tu contraseña',
            inputAttributes: { autocomplete: 'current-password' },
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#c0392b',
            inputValidator: (value) => {
                if (!value) return 'Debes ingresar tu contraseña.';
            },
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = formAction;

                const csrf = document.createElement('input');
                csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = csrfToken;

                const method = document.createElement('input');
                method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';

                const password = document.createElement('input');
                password.type = 'hidden'; password.name = 'password'; password.value = result.value;

                form.append(csrf, method, password);
                document.body.appendChild(form);
                form.submit();
            }
        }),

    confirm: (mensaje, detalle = '') =>
        OcreSwal.fire({
            title: mensaje,
            text: detalle,
            icon: 'question',
            showCancelButton: true,
        }),

    info: (mensaje, detalle = '') =>
        OcreSwal.fire({
            title: mensaje,
            text: detalle,
            icon: 'info',
        }),

    success: (mensaje, detalle = '') =>
        OcreSwal.fire({
            title: mensaje,
            text: detalle,
            icon: 'success',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        }),
};
