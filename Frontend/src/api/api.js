import axios from "axios";

const api = axios.create({
    baseURL: "http://localhost",
    headers: {
        "Content-Type": "application/json"
    }
});

// Agrega el token automáticamente a cada request
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Si el backend responde 401, limpia la sesión
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            window.dispatchEvent(new Event("unauthorized"));
        }
        return Promise.reject(error);
    }
);

export default api;