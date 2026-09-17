from sklearn.metrics import cohen_kappa_score
from typing import List, Dict, Optional


def compute_agreement(pairs_a: List[Dict], pairs_b: List[Dict]) -> Dict:
    """
    Compute inter-annotator agreement between two annotators.
    
    Args:
        pairs_a: List of annotation dicts from annotator A
        pairs_b: List of annotation dicts from annotator B
    
    Returns:
        Dict with matched count, Cohen's kappa, and average Jaccard similarity
    """
    by_clip_b = {r["clip_id"]: r for r in pairs_b}
    matched_a, matched_b, jaccards = [], [], []
    
    for a in pairs_a:
        b = by_clip_b.get(a["clip_id"])
        if not b:
            continue
        
        matched_a.append(a["naturalness"])
        matched_b.append(b["naturalness"])
        
        set_a, set_b = set(a["prosody_tags"]), set(b["prosody_tags"])
        union = set_a | set_b
        jaccards.append(len(set_a & set_b) / len(union) if union else 1.0)
    
    if not matched_a:
        return {"matched": 0, "kappa": None, "avg_jaccard": None}
    
    kappa = cohen_kappa_score(matched_a, matched_b, labels=[1, 2, 3, 4, 5])
    
    return {
        "matched": len(matched_a),
        "kappa": float(kappa),
        "avg_jaccard": float(sum(jaccards) / len(jaccards)),
    }


def interpret_kappa(kappa: float) -> str:
    """
    Interpret Cohen's kappa according to Landis & Koch scale.
    
    Returns:
        Human-readable interpretation label
    """
    if kappa < 0:
        return "Poor"
    elif kappa < 0.20:
        return "Slight"
    elif kappa < 0.40:
        return "Fair"
    elif kappa < 0.60:
        return "Moderate"
    elif kappa < 0.80:
        return "Substantial"
    else:
        return "Almost Perfect"
