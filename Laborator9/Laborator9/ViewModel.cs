using System;
using Laborator9.Models;
using System.Collections.Generic;
using System.Linq;
using System.Web.Mvc;
using MySql.Data.MySqlClient;

namespace Laborator9
{
    public class ViewModel
    {
        public IEnumerable<Product> Products { get; set; }
        public int ProductPerPage { get; set; }
        public int CurrentPage { get; set; }

        public int PageCount()
        {
            return Convert.ToInt32(Math.Ceiling(Products.Count() / (double)ProductPerPage));
        }

        public IEnumerable<Product> PaginatedProducts()
        {
            int start = (CurrentPage - 1) * ProductPerPage;

            IEnumerable<Product> result = Products.Skip(start).Take(ProductPerPage);

            return result;
        }
    }
}
