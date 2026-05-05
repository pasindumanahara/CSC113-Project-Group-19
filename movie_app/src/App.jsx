import { useState } from "react";
import { Routes, Route, Navigate } from "react-router-dom";

import SignUp from "./pages/SignUp";
import Login from "./pages/Login";
import NavBarNormal from "./components/NavBarNormal";
import NavBar from "./components/NavBar";
//import Home from "./pages/Home";
import AboutProject from "./pages/AboutProject";
import SearchItem from "./components/SearchItem";
import Details from "./components/Details";
import DetailPanes from "./components/DetailPanes";


function ProtectedRoute({ children }) {
  // Authenticating flag
  const isLoggedIn = localStorage.getItem("isLoggedIn"); 

  if (!isLoggedIn) {
    // Redirect to login if not logged in
    return <Navigate to="/" replace />; 
  }

  return children;
}

export default function App() {
  const [query, setQuery] = useState("");
  const [movies, setMovies] = useState([]);
  const [loading, setLoading] = useState(false);

  // Enter your own API key 
  const API_KEY = "";

  async function searchMovies(e) {
    e.preventDefault();
    if (!query) return;

    setLoading(true);
    const url = `https://api.themoviedb.org/3/search/multi?api_key=${API_KEY}&query=${encodeURIComponent(query)}`;

    try {
      const res = await fetch(url);
      const data = await res.json();
      setMovies(data.results);
      console.log(data);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  }

  return (
    <Routes>
      {/* Login Page */}
      <Route path="/" element={<Login />} />

      {/* Home page with nav bar and search functionality */}
      <Route
        path="/home"
        element={
          <ProtectedRoute>
            <NavBar query={query} setQuery={setQuery} searchMovies={searchMovies} />
            <DetailPanes movies={movies} loading={loading} />
          </ProtectedRoute>
        }
      />

      {/* Sign up */}
      <Route path="/signup" element={<SignUp />} />
   

      <Route path="/aboutproject" element={
        <AboutProject />}/>
      
    </Routes>
  );
}
