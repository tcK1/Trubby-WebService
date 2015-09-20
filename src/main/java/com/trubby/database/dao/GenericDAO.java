package com.trubby.database.dao;

import java.util.HashSet;
import java.util.Iterator;
import java.util.List;
import java.util.Set;
import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.TypedQuery;
import javax.persistence.criteria.CriteriaBuilder;
import javax.persistence.criteria.CriteriaQuery;

import org.springframework.stereotype.Component;
import org.springframework.transaction.annotation.Propagation;
import org.springframework.transaction.annotation.Transactional;

@Component
public abstract class GenericDAO<T, K> {
	
	@PersistenceContext(unitName="trubby")
	protected EntityManager em;
	
	public void insert(T obj) {
		Set<T> objs = new HashSet<T>();
		objs.add(obj);
		this.insert(objs);
	}
	
	@Transactional(propagation=Propagation.REQUIRED)
	public void insert(Set<T> objs) {
		Iterator<T> it = objs.iterator();
		
		while(it.hasNext()) {
			this.em.persist(it.next());
		}
	}
	
	@Transactional(propagation=Propagation.REQUIRED)
	public void delete(Class<T> cl, K pk) {
		this.delete(this.select(cl, pk));
	}
	
	@Transactional(propagation=Propagation.REQUIRED)
	public void delete(T obj) {
		Set<T> objs = new HashSet<T>();
		objs.add(obj);
		this.delete(objs);
	}
	
	@Transactional(propagation=Propagation.REQUIRED)
	public void delete(Set<T> objs) {
		Iterator<T> it = objs.iterator();
		
		while(it.hasNext()) {
			this.em.remove(it.next());
		}
	}
	
	@Transactional(propagation=Propagation.REQUIRED)
	public void update(T obj) {
		Set<T> objs = new HashSet<T>();
		objs.add(obj);
		this.update(objs);
	}
	
	@Transactional(propagation=Propagation.REQUIRED)
	public void update(Set<T> objs) {
		Iterator<T> it = objs.iterator();
		
		while(it.hasNext()) {
			this.em.merge(it.next());
		}
	}
	
	@Transactional(propagation=Propagation.SUPPORTS)
	public T select(Class<T> cl, K pk) {
		return this.em.find(cl, pk);
	}
	
	@Transactional(propagation=Propagation.SUPPORTS)
	public List<T> selectAll(Class<T> cl) {
		CriteriaBuilder cb = this.em.getCriteriaBuilder();
		CriteriaQuery<T> cq = cb.createQuery(cl);
		cq.select(cq.from(cl));
		TypedQuery<T> q = this.em.createQuery(cq);
		return q.getResultList();
	}
}
