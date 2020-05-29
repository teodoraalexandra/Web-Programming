package mvcIntelliJIdea.images;

import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;

public class DisplayImage extends HttpServlet {
    public final String imagesBase = "/Users/teodoradan/Desktop/Laboratoare Web/Laborator 8/web/images/";

    public void doGet(HttpServletRequest request,HttpServletResponse response) throws IOException{
        String URLAfterWebDomain = request.getRequestURI();

        //Only accept mappings as src="/images/example.jpg"
        if(URLAfterWebDomain.startsWith("/images/") == false)
            return;

        //get the image name, or even directory and image, e.g. /images/music/beethoven.jpg:
        String relativeImagePath = URLAfterWebDomain.substring("/images/".length());  //will get "music/beethoven.jpg"

        response.setContentType("image/jpeg");

        ServletOutputStream outStream;
        outStream = response.getOutputStream();
        FileInputStream fin = new FileInputStream(imagesBase+relativeImagePath);

        BufferedInputStream bin = new BufferedInputStream(fin);
        BufferedOutputStream bout = new BufferedOutputStream(outStream);
        int ch =0; ;
        while((ch=bin.read())!=-1)
            bout.write(ch);

        bin.close();
        fin.close();
        bout.close();
        outStream.close();
    }
}
