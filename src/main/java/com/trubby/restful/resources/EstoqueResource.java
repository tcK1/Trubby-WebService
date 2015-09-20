package com.trubby.restful.resources;

import javax.ws.rs.Consumes;
import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import com.trubby.database.model.Estoque;
import com.trubby.facade.EstoqueFacade;

@Path("/estoque")
@Component
public class EstoqueResource {

	@Autowired
	private EstoqueFacade facade;
	
	@Path("{idUsuario}")
	@GET
	@Consumes(MediaType.TEXT_PLAIN)
	@Produces(MediaType.APPLICATION_JSON)
	public Estoque[] getEstoqueUsuario(@PathParam("idUsuario") Long idUsuario) {
		return this.facade.selecionarEstoqueUsuario(idUsuario).toArray(new Estoque[0]);
	}
}
