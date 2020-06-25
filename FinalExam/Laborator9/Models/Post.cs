using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.ComponentModel.DataAnnotations;

namespace ExamTemplateAsp.Models
{
    public class Post
    {
        public int Id { get; set; }

        [DisplayName("Username")]
        [Required(ErrorMessage = "This field is required.")]
        public string Username { get; set; }

        public int TopicId { get; set; }
        public string Text { get; set; }
        public DateTime Date { get; set; }
    } 
}
