// Load user theme from SQL backend on any page
fetch("../php/get_theme.php")
  .then((res) => res.json())
  .then((data) => {
    if (data.preference === "dark") {
      document.body.classList.add("dark-mode");
    } else {
      document.body.classList.remove("dark-mode");
    }
  });

document.getElementById('designButton').addEventListener('click', function() {
  var hiddenContainer = document.getElementById('cssButtonsContainer');
  if (hiddenContainer.style.display === 'none') {
    hiddenContainer.style.display = 'block'; // Or 'flex', 'inline-block' depending on desired layout
  } else {
    hiddenContainer.style.display = 'none';
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const layoutSelector = document.getElementById('cssButtonsContainer');
  const contentContainer = document.getElementById('portfolio-container');

  // Function to apply the selected layout
  function applyLayout(layoutType) {
    contentContainer.className = ''; // Clear existing classes
    contentContainer.classList.add(layoutType);
  }

  // Initial layout application (e.g., set to grid by default)
  applyLayout(layoutSelector.value);

  // Event listener for select menu changes
  layoutSelector.addEventListener('change', (event) => {
    applyLayout(event.target.value);
  });
});

function changeBodyFont() {
  const selectElement = document.getElementById('fonts');
  const selectedFont = selectElement.value;
  document.body.style.fontFamily = selectedFont;
}

const colorSelect = document.getElementById('colors');

colorSelect.addEventListener('change', function() {
  const selectedColor = this.value;
 let bodyColor = '';

  switch (selectedColor) {
  case 'standard':
    bodyColor = '#bcd4f3'; // Standard
    break;
  case 'reddish':
    bodyColor = '#FFCCCC'; // Light red
    break;
  case 'yellowish':
    bodyColor = '#fff292'; // Light Yellow
    break;
  case 'greenish':
    bodyColor = '#CCFFCC'; // Light green
    break;
  case 'bluish':
    bodyColor = '#a8d0ff'; // Light blue
    break;
  case 'greyish':
    bodyColor = '#ccc'; // Light grey
    break;
  case 'dark':
    bodyColor = '#2b3745'; // Dark
    break;
  }
  document.body.style.backgroundColor = bodyColor;
});
