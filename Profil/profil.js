// Switch between tabs
function switchTab(tabName) {
  // Hide all tab contents
  const contents = document.querySelectorAll('.tab-content');
  contents.forEach(content => content.classList.remove('active'));

  // Remove active class from all buttons
  const buttons = document.querySelectorAll('.tab-btn');
  buttons.forEach(button => button.classList.remove('active'));

  // Show selected tab content
  const selectedContent = document.getElementById(tabName + '-section');
  if (selectedContent) {
    selectedContent.classList.add('active');
  }

  // Add active class to clicked button
  event.target.classList.add('active');
}

// Navigation
function navigate(element) {
  document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
  element.classList.add('active');
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  // Set first tab as active
  document.querySelector('.tab-btn').classList.add('active');
});