import { NavLink } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import "./NavBar.css";

const NavBarComponent = () => {

    const { user, isAuthenticated, logout, loading } = useAuth();

    if(loading) return null;

    return (
        <>
            {isAuthenticated ? (
                <nav className="navBar_container">
                    <ul className="navBar_menu">
                        <li className="navBar_item">
                            <NavLink 
                                to={"/stat"}
                                className={({isActive}) => isActive ? "link_active" : "link"}
                            >
                                Activos
                            </NavLink>
                        </li>
                        <li className="navBar_item">
                            <NavLink 
                                to={"/panel"}
                                className={({isActive}) => isActive ? "link_active" : "link"}
                            >
                                Panel
                            </NavLink>
                        </li>
                        <li className="navBar_item">
                            <NavLink 
                                to={"/portfolio"}
                                className={({isActive}) => isActive ? "link_active" : "link"}
                            >
                                Portfolio
                            </NavLink>
                        </li>
                        <li className="navBar_item">
                            <NavLink 
                                to={"/transactions"}
                                className={({isActive}) => isActive ? "link_active" : "link"}
                            >
                                Operaciones
                            </NavLink>
                        </li>
                        <li className="navBar_item">
                            <NavLink 
                                to={`/user-edit/${user.id}`}
                                className={({isActive}) => isActive ? "link_active" : "link"}
                            >
                                Perfil
                            </NavLink>
                        </li>
                        {Number(user.admin) === 1 && (
                        <li className="navBar_item">
                            <NavLink 
                                to={"/user-managment"}
                                className={({isActive}) => isActive ? "link_active" : "link"}
                            >
                                Admin
                            </NavLink>
                        </li>
                        )}
                        <li className="navBar_item">
                            <button 
                                className="navBar_logout"
                                onClick={logout}
                            >
                                Cerrar Sesión
                            </button>
                        </li>
                    </ul>
                </nav>
            ) : (
                <nav className="navBar_logout_buttons">
                    <button className="navBar_login_button">
                        <NavLink to={"/login"} className="button_text">Inciar Sesión</NavLink>
                    </button>
                    <button className="navBar_register_button">
                        <NavLink to={"/register"} className="button_text">Registrarse</NavLink>
                    </button>
                </nav>
            )}
        </>
    )
} 

export default NavBarComponent;