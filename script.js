// Product data - Computer Peripherals
const products = [
  {
    id: 1,
    title: 'Ergonomic Split Keyboard',
    desc: 'Split design keyboard with adjustable tenting and wrist support. Perfect for users with carpal tunnel or repetitive strain injuries.',
    tags: ['mobility'],
    price: 129.99,
    img: 'https://via.placeholder.com/400x250?text=Ergonomic+Keyboard',
    rating: 4.8,
    reviews: 142
  },
  {
    id: 2,
    title: 'Trackball Mouse Pro',
    desc: 'Precision trackball mouse with large ball and customizable buttons. Reduces wrist movement and strain.',
    tags: ['mobility'],
    price: 79.99,
    img: 'https://via.placeholder.com/400x250?text=Trackball+Mouse',
    rating: 4.6,
    reviews: 89
  },
  {
    id: 3,
    title: 'High-Contrast Keyboard',
    desc: 'Keyboard with large, high-contrast keys and backlighting. Ideal for users with low vision.',
    tags: ['vision'],
    price: 89.99,
    img: 'https://via.placeholder.com/400x250?text=High+Contrast+Keyboard',
    rating: 4.9,
    reviews: 203
  },
  {
    id: 4,
    title: 'Voice Control Headset',
    desc: 'Noise-cancelling headset with voice control software for hands-free computer operation.',
    tags: ['mobility'],
    price: 149.99,
    img: 'https://via.placeholder.com/400x250?text=Voice+Control+Headset',
    rating: 4.5,
    reviews: 67
  },
  {
    id: 5,
    title: 'Large Button Mouse',
    desc: 'Oversized mouse with high-contrast buttons and scroll wheel. Easy to see and operate for users with motor challenges.',
    tags: ['vision', 'mobility'],
    price: 49.99,
    img: 'https://via.placeholder.com/400x250?text=Large+Button+Mouse',
    rating: 4.7,
    reviews: 156
  },
  {
    id: 6,
    title: 'Foot Pedal Controller',
    desc: 'Programmable foot pedal for keyboard shortcuts and commands. Frees up hands for other tasks.',
    tags: ['mobility'],
    price: 69.99,
    img: 'https://via.placeholder.com/400x250?text=Foot+Pedal',
    rating: 4.4,
    reviews: 98
  },
  {
    id: 7,
    title: 'Braille Display',
    desc: 'Refreshable braille display that connects to computers and mobile devices. Supports screen reader output.',
    tags: ['vision'],
    price: 899.99,
    img: 'https://via.placeholder.com/400x250?text=Braille+Display',
    rating: 4.9,
    reviews: 45
  },
  {
    id: 8,
    title: 'Eye Tracking System',
    desc: 'Advanced eye tracking technology for hands-free computer control. Ideal for users with limited mobility.',
    tags: ['mobility'],
    price: 299.99,
    img: 'https://via.placeholder.com/400x250?text=Eye+Tracking',
    rating: 4.7,
    reviews: 78
  }
];

// DOM elements
const productGrid = document.getElementById('productGrid');
const searchInput = document.getElementById('searchInput');
const filter = document.getElementById('filter');
const searchBtn = document.getElementById('searchBtn');
const cartCount = document.getElementById('cartCount');
const openSearch = document.getElementById('openSearch');
const openCart = document.getElementById('openCart');

// State
let cart = [];
let filteredProducts = [...products];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
  renderProducts(filteredProducts);
  setupEventListeners();
  updateCartCount();
});

// Event listeners
function setupEventListeners() {
  searchBtn.addEventListener('click', filterProducts);
  searchInput.addEventListener('input', debounce(filterProducts, 300));
  filter.addEventListener('change', filterProducts);
  
  openSearch.addEventListener('click', function() {
    searchInput.focus();
  });
  
  openCart.addEventListener('click', function() {
    alert(`You have ${cart.length} item${cart.length !== 1 ? 's' : ''} in your cart.`);
  });
  
  // FAQ accordion
  document.querySelectorAll('.accordion-button').forEach(btn => {
    btn.addEventListener('click', toggleAccordion);
  });
  
  // Add keyboard navigation to product grid
  productGrid.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.classList.contains('card')) {
      const button = e.target.querySelector('button');
      if (button) button.click();
    }
  });
}

// Product rendering
function renderProducts(list) {
  productGrid.innerHTML = '';
  
  if (list.length === 0) {
    productGrid.innerHTML = `
      <div class="card text-center" style="grid-column: 1 / -1; padding: 3rem;">
        <h3>No products found</h3>
        <p>Try adjusting your search or filter criteria.</p>
        <button class="btn mt-2" onclick="clearFilters()">Clear filters</button>
      </div>
    `;
    return;
  }
  
  list.forEach(product => {
    const card = document.createElement('div');
    card.className = 'card';
    card.tabIndex = 0; // Make focusable for keyboard navigation
    card.setAttribute('role', 'article');
    card.setAttribute('aria-label', `${product.title} - $${product.price.toFixed(2)}`);
    
    // Generate star rating
    const stars = generateStarRating(product.rating);
    
    card.innerHTML = `
      <img src="${product.img}" alt="${product.title}" loading="lazy">
      <div class="card-tags">
        ${product.tags.map(tag => `<span class="tag">${getTagLabel(tag)}</span>`).join('')}
      </div>
      <h3>${product.title}</h3>
      <p>${product.desc}</p>
      <div class="product-rating">
        ${stars}
        <span class="rating-text">${product.rating} (${product.reviews} reviews)</span>
      </div>
      <div class="price">$${product.price.toFixed(2)}</div>
      <button class="btn" onclick="addToCart(${product.id})" aria-label="Add ${product.title} to cart">
        Add to cart
      </button>
    `;
    
    productGrid.appendChild(card);
  });
}

// Generate star rating HTML
function generateStarRating(rating) {
  const fullStars = Math.floor(rating);
  const hasHalfStar = rating % 1 >= 0.5;
  const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
  
  let stars = '';
  
  // Full stars
  for (let i = 0; i < fullStars; i++) {
    stars += '<span class="star full">★</span>';
  }
  
  // Half star
  if (hasHalfStar) {
    stars += '<span class="star half">★</span>';
  }
  
  // Empty stars
  for (let i = 0; i < emptyStars; i++) {
    stars += '<span class="star empty">★</span>';
  }
  
  return stars;
}

// Get human-readable tag labels
function getTagLabel(tag) {
  const tagLabels = {
    'vision': 'Vision Support',
    'mobility': 'Mobility Support',
    'hearing': 'Hearing Support'
  };
  
  return tagLabels[tag] || tag;
}

// Product filtering
function filterProducts() {
  const search = searchInput.value.toLowerCase().trim();
  const tag = filter.value;
  
  filteredProducts = products.filter(product => {
    const matchText = product.title.toLowerCase().includes(search) || 
                     product.desc.toLowerCase().includes(search);
    const matchTag = tag === '' || product.tags.includes(tag);
    return matchText && matchTag;
  });
  
  renderProducts(filteredProducts);
  
  // Announce results to screen readers
  announceResults(filteredProducts.length);
}

// Clear all filters
function clearFilters() {
  searchInput.value = '';
  filter.value = '';
  filteredProducts = [...products];
  renderProducts(filteredProducts);
  announceResults(filteredProducts.length);
}

// Announce results to screen readers
function announceResults(count) {
  const announcement = document.getElementById('announcement') || createAnnouncementElement();
  announcement.textContent = `${count} product${count !== 1 ? 's' : ''} found.`;
}

// Create announcement element for screen readers
function createAnnouncementElement() {
  const announcement = document.createElement('div');
  announcement.id = 'announcement';
  announcement.className = 'sr-only';
  announcement.setAttribute('aria-live', 'polite');
  announcement.setAttribute('aria-atomic', 'true');
  document.body.appendChild(announcement);
  return announcement;
}

// Cart functionality
function addToCart(id) {
  const product = products.find(p => p.id === id);
  if (product) {
    cart.push(product);
    updateCartCount();
    
    // Show confirmation
    showNotification(`${product.title} added to cart!`);
    
    // Animate cart button
    animateCartButton();
  }
}

function updateCartCount() {
  cartCount.textContent = cart.length;
  
  // Update aria-label for screen readers
  const cartBtn = document.getElementById('openCart');
  cartBtn.setAttribute('aria-label', `Open cart with ${cart.length} item${cart.length !== 1 ? 's' : ''}`);
}

function animateCartButton() {
  const cartBtn = document.getElementById('openCart');
  cartBtn.classList.add('pulse');
  setTimeout(() => {
    cartBtn.classList.remove('pulse');
  }, 500);
}

// Show notification
function showNotification(message) {
  // Remove existing notification if any
  const existingNotification = document.querySelector('.notification');
  if (existingNotification) {
    existingNotification.remove();
  }
  
  // Create new notification
  const notification = document.createElement('div');
  notification.className = 'notification';
  notification.textContent = message;
  notification.setAttribute('role', 'alert');
  
  document.body.appendChild(notification);
  
  // Animate in
  setTimeout(() => {
    notification.style.transform = 'translateX(0)';
  }, 10);
  
  // Remove after 3 seconds
  setTimeout(() => {
    notification.style.transform = 'translateX(100%)';
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 300);
  }, 3000);
}

// FAQ accordion
function toggleAccordion(e) {
  const button = e.currentTarget;
  const expanded = button.getAttribute('aria-expanded') === 'true';
  const panel = document.getElementById(button.getAttribute('aria-controls'));
  
  // Close all other accordion items
  document.querySelectorAll('.accordion-button').forEach(btn => {
    if (btn !== button) {
      btn.setAttribute('aria-expanded', 'false');
      const otherPanel = document.getElementById(btn.getAttribute('aria-controls'));
      otherPanel.style.display = 'none';
    }
  });
  
  // Toggle current item
  button.setAttribute('aria-expanded', !expanded);
  panel.style.display = expanded ? 'none' : 'block';
}

// Utility function: debounce
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}