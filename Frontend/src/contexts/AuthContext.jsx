import { createContext, useContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import * as authService from "../services/authService";

const AuthContext = createContext();

export function AuthProvider({ children }) {

    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const navigate = useNavigate();

    const clearSesion = () => {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        setUser(null);
    };

    useEffect(() => {
        
        const validateSesion = async () => {
            const token = localStorage.getItem("token");
            if(!token) {
                setLoading(false);
                return;
            }
            try {
                const data = await authService.getMe();
                const profile = data.user[0];
                setUser({
                    id: profile.id,
                    nombre: profile.name,
                    email: profile.email,
                    admin: profile.is_admin
                });
            } catch {
                clearSesion();
            } finally {
                setLoading(false);
            }
        };
        validateSesion();
        
        const handleUnauthorized = () => {
            clearSesion();
            navigate("/login");
        }    
        window.addEventListener("unauthorized", handleUnauthorized);
        return () => window.removeEventListener("unauthorized", handleUnauthorized);
    }, []);

    const login = (token, userData) => {
        localStorage.setItem("token", token);
        localStorage.setItem("user", JSON.stringify(userData));
        setUser(userData);
    };

    const logout = async () => {
        try {
            await authService.logout();
        } catch {

        }
        clearSesion();
        navigate("/login");
    };

    const isAuthenticated = user !== null;

    return (
            <AuthContext.Provider
                value={{
                    user, 
                    login, 
                    logout, 
                    loading, 
                    isAuthenticated 
                }}
            >
                {children}
            </AuthContext.Provider>
    );
}

export const useAuth = () => useContext(AuthContext);