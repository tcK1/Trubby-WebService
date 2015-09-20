package com.trubby.facade.impl;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import com.trubby.database.dao.EstoqueDAO;
import com.trubby.database.model.Estoque;
import com.trubby.facade.EstoqueFacade;

@Component
public class EstoqueFacadeImpl implements EstoqueFacade {

	@Autowired
	private EstoqueDAO estoqueDAO;
	
	@Override
	public void incluirEstoque(Estoque estoque) {
		this.estoqueDAO.insert(estoque);
	}

	@Override
	public void atualizarEstoque(Estoque estoque) {
		this.estoqueDAO.update(estoque);
	}

	@Override
	public void removerEstoque(Estoque estoque) {
		this.estoqueDAO.delete(estoque);
	}

	@Override
	public List<Estoque> selecionarEstoqueUsuario(Long idUsuario) {
		return this.estoqueDAO.selectEstoqueUsuario(idUsuario);
	}

}
