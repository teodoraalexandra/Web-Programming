using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MySql.Data.MySqlClient;
using Laborator9.Models;
using System.Web.UI.WebControls;
using System.Web.UI;
using PagedList.Mvc;
using PagedList;

namespace Laborator9.Controllers
{
    public class HomeController : Controller
    {
        public ActionResult Index(int page = 1)
        {
            string product_code = Request.Params["product_code"];

            MySql.Data.MySqlClient.MySqlConnection conn;
            string myConnectionString;

            myConnectionString = "datasource=localhost;port=3306;username=root;password=;database=ecommerce";

            List<Product> products = new List<Product>();

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();

                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = conn;
                cmd.CommandText = "select * from products ";
                MySqlDataReader myreader = cmd.ExecuteReader();

                while (myreader.Read())
                {
                    Product product = new Product();
                    product.Id = myreader.GetInt32("id");
                    product.Name = myreader.GetString("product_name");
                    product.Price = myreader.GetInt32("product_price");
                    product.Image = myreader.GetString("product_image");
                    product.Code = myreader.GetString("product_code");
                    products.Add(product);
                }
                myreader.Close();
            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            var productsView = new ViewModel
            {
                ProductPerPage = 4,
                Products = products,
                CurrentPage = page
            };

            return View(productsView);
        }

        public string GetProductsFromCat()
        {
            string product_code = Request.Params["product_code"];

            MySql.Data.MySqlClient.MySqlConnection conn;
            string myConnectionString;

            myConnectionString = "datasource=localhost;port=3306;username=root;password=;database=ecommerce";

            List<Product> products = new List<Product>();

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();

                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = conn;
                cmd.CommandText = "select * from products where product_code='" + product_code + "'";
                MySqlDataReader myreader = cmd.ExecuteReader();

                while (myreader.Read())
                {
                    Product product = new Product();
                    product.Id = myreader.GetInt32("id");
                    product.Name = myreader.GetString("product_name");
                    product.Price = myreader.GetInt32("product_price");
                    product.Image = myreader.GetString("product_image");
                    product.Code = myreader.GetString("product_code");
                    products.Add(product);
                }
                myreader.Close();
            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            ViewData["productList"] = products;

            string result = "<b> Name&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspPrice&nbsp&nbsp&nbsp&nbsp&nbsp&nbspCode </b><br/><br/>";
            result += "<hr></hr>";

            foreach (Product prod in products)
            {
                result += "<br/><form action= '/Home/AddProductToCart' method='GET'>" +
                    "<input type='hidden' id='pname' name='product_name' value='" + prod.Name + "'> " + prod.Name + "</input>&nbsp&nbsp" +
                    "<input type='hidden' id='pprice' name='product_price' value='" + prod.Price + "'>" + prod.Price + "</input>&nbsp&nbsp" +
                    "<input type='hidden' id='pcode' name='product_code' value='" + prod.Code + "'>" + prod.Code + "</input>&nbsp&nbsp" +
                    "<input class='button' type='submit' value='Add product' />" +
                    "</form><br/>";
                result += "<hr></hr>";
            }

            return result;
        }

        public ActionResult AddProductToCart()
        {
            string product_name = Request.Params["product_name"];
            string product_price = Request.Params["product_price"];

            MySql.Data.MySqlClient.MySqlConnection conn;
            string myConnectionString;

            myConnectionString = "datasource=localhost;port=3306;username=root;password=;database=ecommerce";

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();

                //We should check if the product is already in the database
                //It is redundant to have n products of same type. Better update their quantity

                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = conn;
                cmd.CommandText = "select * from cart where product_name='" + product_name + "' and username='" + Session["userName"] + "'";
                MySqlDataReader myreader = cmd.ExecuteReader();

                if (myreader.HasRows)
                {
                    TempData["status"] = "There is already a product in the cart. Just update the quantity.";
                    myreader.Close();
                }
                else
                {  
                    TempData["status"] = "Product added successfully!";
                    myreader.Close();

                    MySqlCommand add = new MySqlCommand();
                    add.Connection = conn;
                    add.CommandText = "insert into cart (product_name, product_price, qty, total_price, username) values('" + product_name + "','" + product_price + "','1','" + product_price + "','" + Session["userName"] + "')";
                    add.ExecuteNonQuery();
                }

            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return RedirectToAction("Index", "Home");
        }

        public ActionResult GoToCart()
        {
            return RedirectToAction("Index", "Cart");
        }

    }
}
