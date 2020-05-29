using System;
namespace Laborator9.Models
{
    public class Cart
    {
        public int Id { get; set; }
        public string Name { get; set; }
        public int Price { get; set; }
        public int Quantity { get; set; }
        public int Total { get; set; }
        public string Username { get; set; }
    }
}
