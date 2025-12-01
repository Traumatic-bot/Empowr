from django.shortcuts import render, get_object_or_404, redirect
from django.contrib.auth import login
from django.contrib.auth.forms import UserCreationForm

from .models import Product, Category, CartItem, Customer

def index(request):
    products = Product.objects.all()
    return render(request, "index.html", {"products": products})

def category_view(request, category_id):
    category = get_object_or_404(Category, id=category_id)
    products = Product.objects.filter(category=category)
    return render(request, "category.html", {
        "category": category,
        "products": products,
    })

def login_view(request):
    return render(request, "login.html")

def signup_view(request):
    if request.method == "POST":
        form = UserCreationForm(request.POST)
        if form.is_valid():
            user = form.save()          # creates row in auth_user table
            login(request, user)        # log them in immediately
            return redirect('index')    # send them to homepage
    else:
        form = UserCreationForm()

    return render(request, "signup.html", {"form": form})


def cart_view(request):
    customer = Customer.objects.first()
    if customer is None:
        cart_items = []
    else:
        cart_items = CartItem.objects.filter(customer=customer)
    return render(request, "cart.html", {
        "customer": customer,
        "cart_items": cart_items,
    })



