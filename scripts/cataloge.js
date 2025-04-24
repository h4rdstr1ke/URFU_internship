function searchPage() {
    const searchTerm = document.getElementById("searchInput").value.toLowerCase();
    const elements = document.querySelectorAll("p, h1, h2, h3"); 

    elements.forEach(el => {
      const text = el.textContent.toLowerCase();
      if (text.includes(searchTerm)) {
        el.style.backgroundColor = "orange";
      } else {
        el.style.backgroundColor = "";
      }
    });
  }