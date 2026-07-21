import HeaderComponent from "../components/Header/HeaderComponent";
import FooterComponent from "../components/Footer/FooterComponent";
import HomeComponent from "../components/Home/HomeComponent";
import "../assets/styles/Page.css";

const HomePage = () => {

    return (
        <main>
            <HeaderComponent />
            <HomeComponent />
            <FooterComponent />
        </main>
    )
}

export default HomePage;