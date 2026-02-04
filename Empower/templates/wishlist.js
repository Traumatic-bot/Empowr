// ===== Empowr Wishlist (localStorage) =====
const WISHLIST_KEY = "empowr_wishlist";

function getWishlist() {
  try {
    return JSON.parse(localStorage.getItem(WISHLIST_KEY)) || [];
  } catch (e) {
    return [];
  }
}

function saveWishlist(items) {
  localStorage.setItem(WISHLIST_KEY, JSON.stringify(items));
  updateWishlistCount();
}

function updateWishlistCount() {
  const el = document.getElementById("wishlistCount");
  if (!el) return;
  el.textContent = getWishlist().length;
}

function addToWishlist(item) {
  const items = getWishlist();
  if (!items.some(x => x.id === item.id)) {
    items.push(item);
    saveWishlist(items);
  }
}

function removeFromWishlist(id) {
  const items = getWishlist().filter(x => x.id !== id);
  saveWishlist(items);
}

function toggleWishlist(item) {
  const items = getWishlist();
  const exists = items.some(x => x.id === item.id);
  if (exists) removeFromWishlist(item.id);
  else addToWishlist(item);
  return !exists;
}
// keeps counter synced upon every load
document.addEventListener("DOMContentLoaded", updateWishlistCount);
