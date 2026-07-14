import { NavLink } from "react-router-dom";
import TitleComponent from "../Title/TitleComponent";
import "./Home.css";

const HomeComponent = () => {

    return (
        <section className="home_container">
            <div>
                <TitleComponent text={"Bienvenido a WallyStreet Investments."} />
            </div>
            <div>
                <button className="home_button">
                    <NavLink
                        to={"/stat"}
                        className="button_link"
                    >
                        Ver listado de activos →
                    </NavLink>
                </button>
            </div>
        </section>
    );
}

export default HomeComponent;