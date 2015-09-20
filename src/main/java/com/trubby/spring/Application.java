package com.trubby.spring;

import org.glassfish.jersey.server.ResourceConfig;
import org.glassfish.jersey.server.spring.scope.RequestContextFilter;

import com.trubby.restful.resources.EstoqueResource;
import com.trubby.restful.resources.UsuariosResource;

public class Application extends ResourceConfig {

	public Application() {
		register(RequestContextFilter.class);
		register(UsuariosResource.class);
		register(EstoqueResource.class);
	}
}
