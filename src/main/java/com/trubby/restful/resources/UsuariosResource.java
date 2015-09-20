package com.trubby.restful.resources;

import javax.ws.rs.Consumes;
import javax.ws.rs.DELETE;
import javax.ws.rs.POST;
//import javax.ws.rs.FormParam;
//import javax.ws.rs.GET;
//import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
//import javax.ws.rs.Produces;
//import javax.ws.rs.QueryParam;
import javax.ws.rs.core.MediaType;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import com.trubby.database.model.Usuarios;
import com.trubby.facade.UsuariosFacade;

@Path("/usuarios")
@Component
public class UsuariosResource {

	@Autowired
	private UsuariosFacade facade;
	
//	@GET
//	@Consumes(MediaType.TEXT_PLAIN)
//	@Produces(MediaType.APPLICATION_JSON)
//	public Product[] getProducts(@QueryParam("limit") int limit) {
//		return thisfacade.selectProducts(limit).toArray(new Product[0]);
//	}
//	
//	@Path("{productId}")
//	@GET
//	@Consumes(MediaType.TEXT_PLAIN)
//	@Produces(MediaType.APPLICATION_JSON)
//	public Product getProduct(@PathParam("productId") Integer productId) {
//		return this.facade.selectProduct(productId);
//	}
//	
//	@POST
//	@Consumes(MediaType.APPLICATION_FORM_URLENCODED)
//	public void incluirUsuario(@FormParam("usuarioId") BigInteger usuarioId,
//			@FormParam("price") Double price,
//			@FormParam("name") String name) {
//		this.facade.insertProduct(productId, price, name);
//	}
	
	@Path("{idUsuario}")
	@POST
	@Consumes(MediaType.APPLICATION_JSON)
	public void incluirUsuario(Usuarios usuario) {
		System.out.println("nome: " + usuario.getNome());
		this.facade.incluirUsuario(usuario);
	}
	
	@Path("{idUsuario}")
	@DELETE
	@Consumes(MediaType.TEXT_PLAIN)
	public void removerUsuario(@PathParam("idUsuario") Long idUsuario) {
		System.out.println("idUsuario: " + idUsuario);
		this.facade.removerUsuario(idUsuario);
	}
}
