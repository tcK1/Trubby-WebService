package com.trubby.database.dao;

import java.util.List;

import javax.persistence.TypedQuery;
import javax.persistence.criteria.CriteriaBuilder;
import javax.persistence.criteria.CriteriaQuery;
import javax.persistence.criteria.Root;

import org.springframework.stereotype.Component;
import org.springframework.transaction.annotation.Propagation;
import org.springframework.transaction.annotation.Transactional;

import com.trubby.database.model.Estoque;

@Component
public class EstoqueDAO extends GenericDAO<Estoque, Long> {

	@Transactional(propagation=Propagation.SUPPORTS)
	public List<Estoque> selectEstoqueUsuario(Long idUsuario) {
		CriteriaBuilder cb = this.em.getCriteriaBuilder();
		CriteriaQuery<Estoque> cq = cb.createQuery(Estoque.class);
		Root<Estoque> from = cq.from(Estoque.class);
		TypedQuery<Estoque> q = this.em.createQuery(
				cq.select(from).where(
						cb.equal(from.get("idUsuario"), idUsuario)));
		return q.getResultList();
	}
}
