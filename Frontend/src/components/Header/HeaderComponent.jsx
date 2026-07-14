import { NavLink } from "react-router-dom";
import NavBarComponent from "../NavBar/NavBarComponent";
import "./Header.css"

const HeaderComponent = () => {

    return (
        <header className="header_container">
            <h2>
                <NavLink 
                    to={"/"}
                    className="brand_text"
                >
                    WallyStreet Investments
                </NavLink>
            </h2>
            <NavBarComponent />
        </header>
    );
}

export default HeaderComponent;