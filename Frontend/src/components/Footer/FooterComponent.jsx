import { NavLink } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import "./Footer.css";

const FooterComponent = () => {

    const { user, isAuthenticated, logout, loading } = useAuth();
    
        if(loading) return null;
    
        // if(!isAuthenticated) {
        //     return (
        //         <nav className="footer_menu">
        //             <button className="">
        //                 <NavLink to={"/login"} className="button_text">Inciar Sesión</NavLink>
        //             </button>
        //             <button className="">
        //                 <NavLink to={"/register"} className="button_text">Registrarse</NavLink>
        //             </button>
        //         </nav>
        //     )
        // };

    return (
        <section className="footer_container">
            <div className="footer_main">
                <div className="footer_title">
                    <p>
                        <NavLink 
                            to={"/"}
                            className="footer_heading"
                        >
                            WallyStreet Investments
                        </NavLink>
                    </p>
                </div>
                <div className="footer_menu_container">
                    {isAuthenticated ? (
                        <ul className="footer_menu">
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
                        </ul>
                    ) : (
                        <ul className="footer_menu">
                            <li className="navBar_item">
                                <NavLink 
                                    to={"/stat"}
                                    className={({isActive}) => isActive ? "link_active" : "link"}
                                >
                                    Activos
                                </NavLink>
                            </li>
                        </ul>
                    )}
                </div>
            </div>
            <p className="footer_copy">Copyright © 2026 - WallyStreet - Investments</p>
        </section>
    );
}

export default FooterComponent;