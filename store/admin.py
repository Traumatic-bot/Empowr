from django.contrib import admin

# Register your models here.

from django.contrib import admin
from .models import Customer, Category, Product, CartItem

admin.site.register(Customer)
admin.site.register(Category)
admin.site.register(Product)
admin.site.register(CartItem)
